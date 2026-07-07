<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalPesanan = Pesanan::where('user_id', $user->id)->count();
        $pesananDiproses = Pesanan::where('user_id', $user->id)->where('status', 'diproses')->count();
        $pesananDikerjakan = Pesanan::where('user_id', $user->id)->where('status', 'dikerjakan')->count();
        $pesananSelesai = Pesanan::where('user_id', $user->id)->where('status', 'selesai')->count();
        $pesananTerbaru = Pesanan::where('user_id', $user->id)->with('details.produk')->latest()->take(5)->get();
        $totalProduk = Produk::count();

        // Hitung metrik kuantitas item produksi
        $pesanans = Pesanan::where('user_id', $user->id)
            ->where('status', '!=', 'batal')
            ->with('details.produk')
            ->get();

        $pcsBelumDikerjakan = 0;
        $pcsSedangDiproses = 0;
        $pcsSelesai = 0;

        $breakdownRaw = [];

        foreach ($pesanans as $pesanan) {
            foreach ($pesanan->details as $detail) {
                $key = ($detail->produk_id) . '-' . ($detail->ukuran);
                if (!isset($breakdownRaw[$key])) {
                    $breakdownRaw[$key] = [
                        'produk' => $detail->produk->nama_produk ?? 'Seragam',
                        'ukuran' => $detail->ukuran,
                        'total_pesanan' => 0,
                        'belum_dikerjakan' => 0,
                        'sedang_diproses' => 0,
                        'selesai' => 0,
                        'total_terbayar_pcs' => 0,
                    ];
                }

                $totalItem = $detail->total_item;
                $jumlahTerbayar = $detail->jumlah_terbayar ?? 0;

                if ($pesanan->status === 'selesai') {
                    $pcsSelesai += $totalItem;
                    $breakdownRaw[$key]['total_pesanan'] += $totalItem;
                    $breakdownRaw[$key]['selesai'] += $totalItem;
                    $breakdownRaw[$key]['total_terbayar_pcs'] += $totalItem;
                } else {
                    $belum = max(0, $totalItem - $jumlahTerbayar);
                    $pcsBelumDikerjakan += $belum;
                    $pcsSedangDiproses += $jumlahTerbayar;

                    $breakdownRaw[$key]['total_pesanan'] += $totalItem;
                    $breakdownRaw[$key]['belum_dikerjakan'] += $belum;
                    $breakdownRaw[$key]['sedang_diproses'] += $jumlahTerbayar;
                    $breakdownRaw[$key]['total_terbayar_pcs'] += $jumlahTerbayar;
                }
            }
        }

        // Hitung status pembayaran per item breakdown
        foreach ($breakdownRaw as &$item) {
            if ($item['total_terbayar_pcs'] == 0) {
                $item['status_pembayaran'] = 'Belum dibayar';
            } elseif ($item['total_terbayar_pcs'] >= $item['total_pesanan']) {
                $item['status_pembayaran'] = 'Lunas';
            } else {
                $pct = round(($item['total_terbayar_pcs'] / $item['total_pesanan']) * 100);
                $item['status_pembayaran'] = "DP {$pct}%";
            }
        }

        $breakdown = array_values($breakdownRaw);

        return view('pelanggan.dashboard', compact(
            'totalPesanan',
            'pesananDiproses',
            'pesananDikerjakan',
            'pesananSelesai',
            'pesananTerbaru',
            'totalProduk',
            'pcsBelumDikerjakan',
            'pcsSedangDiproses',
            'pcsSelesai',
            'breakdown'
        ));
    }
}
