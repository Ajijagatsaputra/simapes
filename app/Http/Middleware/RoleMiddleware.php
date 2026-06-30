<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            // Redirect ke halaman yang sesuai dengan role user yang sedang login
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            if ($user->role === 'pelanggan') {
                return redirect()->route('pelanggan.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Role tidak valid. Silakan login kembali.');
        }

        return $next($request);
    }
}
