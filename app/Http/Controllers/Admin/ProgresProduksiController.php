<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\ProgresProduksi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgresProduksiController extends Controller
{
    /** 5 tahapan tetap */
    const TAHAPAN = [
        1 => 'Persiapan Bahan',
        2 => 'Pemotongan Bahan',
        3 => 'Penjahitan Baju',
        4 => 'Packing / Finishing',
        5 => 'Selesai',
    ];

    public function show($id)
    {
        $pesanan = Pesanan::with(['details.produk', 'progresProduksis', 'statusLogs'])->findOrFail($id);

        if (!in_array($pesanan->status, ['dikerjakan', 'selesai'])) {
            return redirect()->route('admin.pesanan.index')
                ->with('error', 'Progres produksi hanya dapat dikelola saat status pesanan "Dikerjakan" atau "Selesai".');
        }

        $totalPcs = $pesanan->details->sum('total_item');

        // Bangun 5 slot fixed — jika sudah ada record di DB, gunakan itu; jika belum, buat slot kosong
        $existingByTahapan = $pesanan->progresProduksis->keyBy('tahapan_ke');
        $slots = [];
        foreach (self::TAHAPAN as $ke => $nama) {
            $slots[$ke] = $existingByTahapan->get($ke) ?? null;
        }

        return view('admin.pesanan.progres', compact('pesanan', 'totalPcs', 'slots'));
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::with('details')->findOrFail($id);
        $totalPcs = $pesanan->details->sum('total_item');

        // Validasi: hanya tahap yang dikirim lewat POST
        $request->validate([
            'tahapan_ke'   => 'required|integer|between:1,5',
            'jumlah_pcs'   => "required|integer|min:0|max:{$totalPcs}",
            'dokumentasi'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'      => 'nullable|string|max:1000',
            'tandai_selesai' => 'nullable|boolean',
        ], [
            'jumlah_pcs.max' => "Jumlah pcs tidak boleh melebihi total target pesanan ({$totalPcs} pcs).",
        ]);

        $ke = (int) $request->tahapan_ke;

        // ── Validasi Urutan: tahap sebelumnya harus selesai ──
        if ($ke > 1) {
            $prevSelesai = ProgresProduksi::where('pesanan_id', $pesanan->id)
                ->where('tahapan_ke', $ke - 1)
                ->whereNotNull('selesai_pada')
                ->exists();

            if (!$prevSelesai) {
                $prevNama = self::TAHAPAN[$ke - 1] ?? "Tahap " . ($ke - 1);
                return redirect()->back()->with('error', "Tahap {$ke} belum bisa diisi. Selesaikan dulu Tahap " . ($ke - 1) . " ({$prevNama}) terlebih dahulu.");
            }
        }

        DB::beginTransaction();
        try {
            // Cari atau buat slot untuk tahapan ini
            $progres = ProgresProduksi::firstOrNew([
                'pesanan_id' => $pesanan->id,
                'tahapan_ke' => $ke,
            ]);

            $progres->tahapan    = self::TAHAPAN[$ke];
            $progres->jumlah_pcs = $request->jumlah_pcs;
            $progres->catatan    = $request->catatan;

            // Tandai selesai
            $tandaiSelesai = filter_var($request->tandai_selesai, FILTER_VALIDATE_BOOLEAN);
            if ($tandaiSelesai && is_null($progres->selesai_pada)) {
                $progres->selesai_pada = now();
            } elseif (!$tandaiSelesai) {
                $progres->selesai_pada = null;
            }

            // Upload file dokumentasi / nota
            if ($request->hasFile('dokumentasi')) {
                // Hapus file lama jika ada
                if ($progres->dokumentasi && Storage::disk('public')->exists($progres->dokumentasi)) {
                    Storage::disk('public')->delete($progres->dokumentasi);
                }
                $folder = $ke === 5 ? 'nota_produksi' : 'dokumentasi_produksi';
                $progres->dokumentasi = $request->file('dokumentasi')->store($folder, 'public');
            }

            $progres->save();

            ActivityLog::log(
                'Update progres produksi Tahap ' . $ke . ' (' . self::TAHAPAN[$ke] . ') pesanan: ' . $pesanan->no_pesanan,
                'Pesanan',
                $pesanan->id
            );

            DB::commit();
            return redirect()->route('admin.pesanan.progres', $pesanan->id)
                ->with('success', 'Tahap ' . $ke . ' — ' . self::TAHAPAN[$ke] . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui progres: ' . $e->getMessage())->withInput();
        }
    }
}
