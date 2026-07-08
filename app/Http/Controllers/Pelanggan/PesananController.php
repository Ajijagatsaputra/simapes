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

    /** Simpan pesanan baru dari pelanggan */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.ukuran' => 'required|in:S,M,L,XL,XXL,3XL,4XL,5XL',
            'items.*.total_item' => 'required|integer|min:1',
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
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga * $item['total_item'];
                $totalHarga += $subtotal;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'ukuran' => $item['ukuran'],
                    'harga_satuan' => $produk->harga,
                    'total_item' => $item['total_item'],
                    'subtotal' => $subtotal,
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
                'pembayarans' => fn($q) => $q->where('status', 'verified')->orderBy('termin_ke'),
                'pembayarans.details.detailPesanan.produk',
            ])
            ->findOrFail($id);

        return view('pelanggan.pesanan.show', compact('pesanan'));
    }

    /** Download template Excel */
    public function downloadTemplate()
    {
        //Implementasi export Excel (butuh package maatwebsite/excel)
        return redirect()->back()->with('info', 'Fitur download template Excel segera hadir.');
    }

    /** Upload file Excel pesanan */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        //Implementasi import Excel
        return redirect()->back()->with('info', 'Fitur upload Excel segera hadir.');
    }
}
