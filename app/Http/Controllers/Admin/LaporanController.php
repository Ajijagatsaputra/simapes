<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $sekolahSelected = $request->input('sekolah', 'semua');
        $progressSelected = $request->input('progress', 'semua');
        $keuanganSelected = $request->input('keuangan', 'semua');

        $query = $this->buildQuery($request);

        // Fetch sum of total_harga across ALL matching orders
        $totalPendapatan = $query->sum('total_harga');

        // Fetch matching orders with pagination
        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->paginate(10)->withQueryString();

        // Get distinct list of school names from pelanggan role users
        $listSekolah = \App\Models\User::whereRole('pelanggan')
            ->whereNotNull('nama_sekolah')
            ->where('nama_sekolah', '!=', '')
            ->distinct()
            ->orderBy('nama_sekolah')
            ->pluck('nama_sekolah');

        return view('admin.laporan.index', compact(
            'pesanan',
            'startDate',
            'endDate',
            'sekolahSelected',
            'progressSelected',
            'keuanganSelected',
            'totalPendapatan',
            'listSekolah'
        ));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $sekolahSelected = $request->input('sekolah', 'semua');
        $progressSelected = $request->input('progress', 'semua');
        $keuanganSelected = $request->input('keuangan', 'semua');

        $query = $this->buildQuery($request);

        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->get();
        $totalPendapatan = $pesanan->sum('total_harga');

        return view('admin.laporan.cetak', compact(
            'pesanan',
            'startDate',
            'endDate',
            'sekolahSelected',
            'progressSelected',
            'keuanganSelected',
            'totalPendapatan'
        ));
    }

    public function excel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $sekolahSelected = $request->input('sekolah', 'semua');
        $progressSelected = $request->input('progress', 'semua');
        $keuanganSelected = $request->input('keuangan', 'semua');

        $query = $this->buildQuery($request);

        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->get();
        $totalPendapatan = $pesanan->sum('total_harga');

        $filename = "Laporan_Pemesanan_" . date('Y-m-d_His') . ".xls";

        $content = view('admin.laporan.excel', compact(
            'pesanan',
            'startDate',
            'endDate',
            'sekolahSelected',
            'progressSelected',
            'keuanganSelected',
            'totalPendapatan'
        ))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ]);
    }

    private function buildQuery(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $sekolahSelected = $request->input('sekolah', 'semua');
        $progressSelected = $request->input('progress', 'semua');
        $keuanganSelected = $request->input('keuangan', 'semua');

        $query = Pesanan::with(['user', 'details.produk'])
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate]);

        if ($sekolahSelected !== 'semua') {
            $query->whereHas('user', function ($q) use ($sekolahSelected) {
                $q->where('nama_sekolah', $sekolahSelected);
            });
        }

        if ($progressSelected === 'sedang_berjalan') {
            $query->whereIn('status', ['pending', 'diproses', 'dikerjakan']);
        } elseif ($progressSelected === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($progressSelected === 'batal') {
            $query->where('status', 'batal');
        }

        if ($keuanganSelected === 'belum_lunas') {
            $query->where('sisa_tagihan', '>', 0);
        } elseif ($keuanganSelected === 'lunas') {
            $query->where('sisa_tagihan', '<=', 0);
        }

        return $query;
    }
}
