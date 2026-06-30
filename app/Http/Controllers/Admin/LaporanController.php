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
        $status = $request->input('status', 'semua');

        $query = Pesanan::with(['user', 'details.produk'])
            ->whereBetween('tanggal_pesanan', [$startDate, $endDate]);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $totalPendapatan = $query->sum('total_harga');
        $pesanan = $query->orderBy('tanggal_pesanan', 'desc')->paginate(10)->withQueryString();

        return view('admin.laporan.index', compact('pesanan', 'startDate', 'endDate', 'status', 'totalPendapatan'));
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

        return view('admin.laporan.cetak', compact('pesanan', 'startDate', 'endDate', 'status', 'totalPendapatan'));
    }
}
