<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk check role admin (master vs regional)
 * 
 * Usage:
 * Route::middleware('admin.role:master')->group(function () { ... });
 * Route::middleware('admin.role:regional')->group(function () { ... });
 */
class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role yang diizinkan (master atau regional)
     */
    public function handle(Request $request, Closure $next, string $role = 'master'): Response
    {
        $admin = Auth::guard('admin')->user();

        // Cek apakah sudah login sebagai admin
        if (!$admin) {
            return redirect()->route('loginAdmin')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu');
        }

        // Cek apakah admin active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('loginAdmin')
                ->with('error', 'Akun admin Anda tidak aktif. Hubungi administrator.');
        }

        // Cek role
        $allowedRoles = explode('|', $role);
        if (!in_array($admin->role, $allowedRoles)) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
