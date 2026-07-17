<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    /** Halaman daftar pesanan aktif pelanggan */
    public function index()
    {
        $user = Auth::user();
        $pesanan = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'diproses', 'dikerjakan'])
            ->with('details.produk')
            ->latest()
            ->paginate(10);

        return view('pelanggan.pesanan.index', compact('pesanan'));
    }

    /** Form buat pesanan baru */
    public function create()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        return view('pelanggan.pesanan.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.ukuran' => 'required|in:S,M,L,XL,XXL,3XL,4XL,5XL',
            'items.*.total_item' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string|max:250',
            'items.*.gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::create([
                'no_pesanan' => Pesanan::generateNoPesanan(),
                'user_id' => Auth::id(),
                'tanggal_pesanan' => now()->format('Y-m-d'),
                'status' => 'pending',
                'total_harga' => 0,
            ]);

            $totalHarga = 0;
            foreach ($request->items as $key => $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga * $item['total_item'];
                $totalHarga += $subtotal;

                $pathGambar = null;
                if ($request->hasFile("items.{$key}.gambar")) {
                    $file = $request->file("items.{$key}.gambar");
                    $pathGambar = $file->store('pesanan/gambar_acuan', 'public');
                }

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'ukuran' => $item['ukuran'],
                    'harga_satuan' => $produk->harga,
                    'total_item' => $item['total_item'],
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                    'path_gambar' => $pathGambar,
                ]);
            }

            $pesanan->update([
                'total_harga' => $totalHarga,
                'sisa_tagihan' => $totalHarga,
            ]);

            DB::commit();
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil diajukan! Nomor pesanan: ' . $pesanan->no_pesanan . '. Pesanan Anda akan ditinjau oleh admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /** Detail pesanan */
    public function show($id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->with([
                'details.produk',
                'pembayarans' => fn($q) => $q->orderBy('termin_ke'),
                'pembayarans.details.detailPesanan.produk',
            ])
            ->findOrFail($id);

        // Perbarui status Xendit invoice yang pending jika ada
        foreach ($pesanan->pembayarans as $pembayaran) {
            if ($pembayaran->status === 'pending' && $pembayaran->xendit_invoice_id) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('XENDIT_API_KEY'), '')
                        ->get('https://api.xendit.co/v2/invoices/' . $pembayaran->xendit_invoice_id);

                    if ($response->successful()) {
                        $xenditStatus = $response->json('status');
                        if (in_array($xenditStatus, ['PAID', 'SETTLED'])) {
                            DB::beginTransaction();
                            $pembayaran->update([
                                'status' => 'verified',
                                'verified_at' => now(),
                            ]);
                            $pesanan->recalculatePembayaran();
                            $pesanan->recalculateItemCoverage();
                            DB::commit();
                        } elseif ($xenditStatus === 'EXPIRED') {
                            DB::beginTransaction();
                            $pembayaran->update([
                                'status' => 'rejected',
                                'catatan' => 'Xendit Invoice Expired',
                            ]);
                            DB::commit();
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal memverifikasi status Xendit: ' . $e->getMessage());
                }
            }
        }

        // Reload relasi setelah pembaruan status
        $pesanan->load([
            'pembayarans' => fn($q) => $q->orderBy('termin_ke'),
            'pembayarans.details.detailPesanan.produk',
        ]);

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    /** Download template CSV (compatible dengan Excel) */
    public function downloadTemplate()
    {
        // Ambil daftar produk aktif untuk dimasukkan ke kolom info di template
        $produkList = Produk::orderBy('nama_produk')->get(['id', 'nama_produk', 'harga']);

        // Build CSV content
        $rows = [];

        // Baris 1: Judul / instruksi
        $rows[] = ['TEMPLATE PEMESANAN MASSAL SERAGAM - SIMAPES'];
        $rows[] = ['Petunjuk: Isi kolom Nama_Produk, Ukuran, Jumlah, dan Catatan. Jangan ubah header baris 5.'];
        $rows[] = ['Ukuran yang valid: S, M, L, XL, XXL, 3XL, 4XL, 5XL'];
        $rows[] = ['']; // baris kosong

        // Baris 5: Header data
        $rows[] = ['No', 'Nama_Produk', 'Ukuran', 'Jumlah', 'Catatan'];

        // Contoh data (3 baris)
        $contoh = [
            ['S', 30, 'Bordir logo OSIS di lengan kanan'],
            ['M', 50, 'Tanpa saku depan'],
            ['XL', 20, 'Bordir nama sekolah di belakang'],
        ];
        $idx = 1;
        foreach ($produkList->take(3) as $i => $p) {
            $rows[] = [$idx++, $p->nama_produk, $contoh[$i][0], $contoh[$i][1], $contoh[$i][2]];
        }

        // Jika produk < 3, isi sisa baris contoh dengan kosong
        for ($j = $produkList->count(); $j < 3; $j++) {
            $rows[] = [$idx++, 'Nama Seragam (contoh)', $contoh[$j][0], $contoh[$j][1], $contoh[$j][2] ?? ''];
        }

        $rows[] = ['']; // baris kosong
        $rows[] = ['--- Daftar Produk Tersedia ---'];
        $rows[] = ['ID', 'Nama Produk', 'Harga Satuan (Rp)'];
        foreach ($produkList as $p) {
            $rows[] = [$p->id, $p->nama_produk, $p->harga];
        }

        // Output sebagai file CSV
        $filename = 'template_pesanan_massal_simapes.csv';
        $handle = fopen('php://temp', 'r+');

        // BOM untuk Excel agar baca UTF-8 dengan benar
        fwrite($handle, "\xEF\xBB\xBF");

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /** Upload & parse file CSV/Excel pesanan massal — kembalikan JSON ke frontend */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        $file = $request->file('file_excel');
        $produkList = Produk::orderBy('nama_produk')->get(['id', 'nama_produk', 'harga']);

        // Buat map nama produk (lowercase) → id & harga untuk pencocokan
        $produkMap = [];
        foreach ($produkList as $p) {
            $produkMap[mb_strtolower(trim($p->nama_produk))] = [
                'id' => $p->id,
                'nama' => $p->nama_produk,
                'harga' => $p->harga,
            ];
        }

        $ukuranValid = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL'];

        $items = [];
        $errors = [];
        $rowNum = 0;
        $dataStarted = false;

        // Buka file sebagai CSV (Excel CSV kompatibel)
        $handle = fopen($file->getRealPath(), 'r');

        // Deteksi delimiter dinamis (koma atau titik koma)
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = ',';
        if ($firstLine !== false) {
            $commas = substr_count($firstLine, ',');
            $semicolons = substr_count($firstLine, ';');
            if ($semicolons > $commas) {
                $delimiter = ';';
            }
        }

        // Hapus BOM jika ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($cols = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowNum++;

            // Skip baris kosong
            if (empty(array_filter($cols, fn($c) => trim($c) !== ''))) {
                continue;
            }

            $first = mb_strtolower(trim($cols[0] ?? ''));

            // Deteksi baris header "No, Nama_Produk, Ukuran, Jumlah"
            if (!$dataStarted && in_array($first, ['no', 'no.'])) {
                $dataStarted = true;
                continue;
            }

            // Skip baris info/instruksi sebelum header ditemukan
            if (!$dataStarted) {
                continue;
            }

            // Skip baris info produk tersedia (setelah data)
            if (str_starts_with($first, '---') || $first === 'id') {
                break;
            }

            // Parse baris data
            $namaProduk = trim($cols[1] ?? '');
            $ukuran = strtoupper(trim($cols[2] ?? ''));
            $jumlah = (int) trim($cols[3] ?? 0);
            $catatan = trim($cols[4] ?? '');

            if ($namaProduk === '')
                continue;

            // Cari produk berdasarkan nama (case-insensitive)
            $key = mb_strtolower($namaProduk);
            if (!isset($produkMap[$key])) {
                // Coba partial match
                $found = null;
                foreach ($produkMap as $mapKey => $mapVal) {
                    if (str_contains($mapKey, $key) || str_contains($key, $mapKey)) {
                        $found = $mapVal;
                        break;
                    }
                }
                if (!$found) {
                    $errors[] = "Baris {$rowNum}: Produk \"{$namaProduk}\" tidak ditemukan di sistem.";
                    continue;
                }
                $produkData = $found;
            } else {
                $produkData = $produkMap[$key];
            }

            if (!in_array($ukuran, $ukuranValid)) {
                $errors[] = "Baris {$rowNum}: Ukuran \"{$ukuran}\" tidak valid (gunakan: S, M, L, XL, XXL, 3XL, 4XL, 5XL).";
                continue;
            }

            if ($jumlah < 1) {
                $errors[] = "Baris {$rowNum}: Jumlah harus minimal 1.";
                continue;
            }

            $items[] = [
                'produk_id' => $produkData['id'],
                'nama' => $produkData['nama'],
                'harga' => $produkData['harga'],
                'ukuran' => $ukuran,
                'jumlah' => $jumlah,
                'catatan' => $catatan,
                'subtotal' => $produkData['harga'] * $jumlah,
            ];
        }

        fclose($handle);

        if (empty($items) && empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data pesanan yang ditemukan dalam file. Pastikan format sesuai template.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'items' => $items,
            'errors' => $errors,
            'message' => count($items) . ' baris berhasil dibaca' . (count($errors) ? ', ' . count($errors) . ' baris dilewati.' : '.'),
        ]);
    }
}
