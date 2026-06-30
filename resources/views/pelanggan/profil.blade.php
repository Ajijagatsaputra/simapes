@extends('layouts.pelanggan')
@section('title', 'Profil Saya - SIMAPES')

@push('styles')
    <style>
        .profil-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 800px) {
            .profil-container {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
        }

        .avatar-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            padding: 12px 0;
        }

        .avatar-circle {
            width: 88px;
            height: 88px;
            background: #4A90D9;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 800;
            box-shadow: 0 4px 16px rgba(74, 144, 217, 0.25);
        }

        .profile-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .profile-school {
            font-size: .82rem;
            color: #6b7e9f;
            background: #f5f8ff;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #dde8f8;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        @media (max-width: 600px) {
            .form-group.full-width {
                grid-column: span 1;
            }
        }

        .form-label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 6px;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 10px 14px;
            font-family: inherit;
            font-size: .85rem;
            color: #1a2b4a;
            background: #f5f8ff;
            border: 1.5px solid #c5d8f5;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: #4A90D9;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.12);
        }

        .form-input:disabled {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #64748b;
            cursor: not-allowed;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit {
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
        }

        .btn-submit:hover {
            background: #3a7bc8;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #1a2b4a;">Profil Akun</h1>
        <p style="font-size: .85rem; color: #6b7e9f; margin-top: 4px;">Kelola informasi profil dan kata sandi akun pelanggan
            Anda</p>
    </div>

    <div class="profil-container">
        {{-- Left Side: Avatar & Summary --}}
        <div class="card">
            <div class="avatar-box">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <span class="profile-school">{{ $user->nama_sekolah ?? 'Pelanggan Mandiri' }}</span>
                <span style="font-size: .78rem; color: #8ca0bf;">Terdaftar sejak:
                    {{ \Carbon\Carbon::parse($user->created_at)->isoFormat('MMMM YYYY') }}</span>
            </div>
        </div>

        {{-- Right Side: Edit Form --}}
        <div class="card">
            <h3
                style="font-size: 1.05rem; font-weight: 700; color: #1a2b4a; margin-bottom: 18px; border-bottom: 1px dashed #e2e8f4; padding-bottom: 12px;">
                Ubah Informasi Profil
            </h3>

            <form method="POST" action="{{ route('pelanggan.profil.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    {{-- Nama --}}
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}"
                            required>
                        @error('name')
                            <p style="color: #e53935; font-size: .75rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (Read-only) --}}
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" class="form-input" value="{{ $user->email }}" disabled>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="form-group">
                        <label class="form-label" for="no_whatsapp">No. WhatsApp</label>
                        <input type="text" id="no_whatsapp" name="no_whatsapp" class="form-input"
                            value="{{ old('no_whatsapp', $user->no_whatsapp) }}">
                        @error('no_whatsapp')
                            <p style="color: #e53935; font-size: .75rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Sekolah --}}
                    <div class="form-group">
                        <label class="form-label" for="nama_sekolah">Sekolah / Instansi</label>
                        <input type="text" id="nama_sekolah" name="nama_sekolah" class="form-input"
                            value="{{ old('nama_sekolah', $user->nama_sekolah) }}">
                        @error('nama_sekolah')
                            <p style="color: #e53935; font-size: .75rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group full-width">
                        <label class="form-label" for="alamat">Alamat Lengkap Pengiriman</label>
                        <textarea id="alamat" name="alamat"
                            class="form-textarea">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <p style="color: #e53935; font-size: .75rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <div class="form-group full-width"
                        style="border-top: 1px dashed #e2e8f4; margin-top: 8px; padding-top: 16px;">
                        <h4 style="font-size: .9rem; font-weight: 700; color: #1a2b4a;">Ganti Password (Opsional)</h4>
                        <p style="font-size: .75rem; color: #6b7e9f; margin-top: 2px;">Kosongkan jika Anda tidak ingin
                            merubah password</p>
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-group">
                        <label class="form-label" for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p style="color: #e53935; font-size: .75rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                    <button type="submit" class="btn-submit">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection