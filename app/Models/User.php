<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh di-mass assign.
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'no_whatsapp',
        'alamat',
        'nama_sekolah',
    ];

    /** Cek apakah user adalah Admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Cek apakah user adalah Pelanggan */
    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    /**
     * Kolom yang disembunyikan dari serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
