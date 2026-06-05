<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin() {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function showSignup() {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('home');
        }
        return view('auth.signup');
    }

    public function handleLogin(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.'])->withInput();
        }

        if ($user->role === 'admin') {
            return back()->withErrors(['email' => 'Gunakan halaman login admin untuk akun administrator.'])->withInput();
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['password' => 'Password yang kamu masukkan salah.'])->withInput();
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('home')->with('flash_success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function handleSignup(Request $request) {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'phone_number'     => 'required|string|min:8|max:20',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'password'     => $data['password'],
            'role'         => 'customer',
            'address'      => '',
        ]);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('flash_success', 'Akun berhasil dibuat. Selamat bergabung, ' . $user->name . '!');
    }

    public function handleLogout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('flash_success', 'Kamu telah keluar dari akun.');
    }
}
