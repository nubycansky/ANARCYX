<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->withErrors(['login_error' => 'Silakan login untuk mengakses panel admin.']);
        }

        if (Auth::guard('admin')->user()->role !== 'admin') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')
                ->withErrors(['login_error' => 'Akun ini tidak memiliki akses administrator.']);
        }

        return $next($request);
    }
}
