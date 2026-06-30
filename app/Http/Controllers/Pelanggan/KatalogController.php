<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class KatalogController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();

        return view('pelanggan.katalog', compact('produk'));
    }
}
