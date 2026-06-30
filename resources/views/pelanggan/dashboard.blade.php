@extends('layouts.pelanggan')
@section('title', 'Dashboard - SIMAPES Pelanggan')

@section('content')
    <div style="padding: 32px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">
            Selamat datang, {{ auth()->user()->name }}! 👋
        </h1>
        <p style="color: #6b7e9f;">Dashboard pelanggan sedang dalam pengembangan. Fitur lengkap segera hadir.</p>
    </div>
@endsection