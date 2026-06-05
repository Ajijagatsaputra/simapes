<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'semua');

        $query = Pesanan::with(['user', 'details.produk'])
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate]);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        // Hitung total pendapatan dari seluruh data terfilter
        $totalPendapatan = $query->sum('total_harga');

        // Paginasikan hasil pesanan dan pertahankan query string filter
        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->paginate(10)->withQueryString();

        return view('laporan.index', compact('pesanan', 'startDate', 'endDate', 'status', 'totalPendapatan'));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'semua');

        $query = Pesanan::with(['user', 'details.produk'])
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate]);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->get();
        $totalPendapatan = $pesanan->sum('total_harga');

        return view('laporan.cetak', compact('pesanan', 'startDate', 'endDate', 'status', 'totalPendapatan'));
    }
}
