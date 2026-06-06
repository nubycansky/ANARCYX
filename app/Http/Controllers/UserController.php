<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $orders = Order::where('customer_phone', $user->phone_number)
            ->orWhere('customer_name', $user->name)
            ->orderBy('created_at', 'desc')
            ->get();
        $reviews = Review::where('customer_name', $user->name)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.profile', compact('user', 'orders', 'reviews'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100|unique:users,email,' . Auth::id(),
            'phone_number' => 'required|string|min:8|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('user.profile')->with('flash_success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6',
            'new_password_confirmation' => 'required|string',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->to(url()->previous() . '#password-section')
                ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']))
                ->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        if ($request->new_password !== $request->new_password_confirmation) {
            return redirect()->to(url()->previous() . '#password-section')
                ->withInput($request->except(['new_password_confirmation']))
                ->withErrors(['new_password_confirmation' => 'Konfirmasi password baru tidak cocok.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('user.profile')->with('flash_success', 'Password berhasil diubah.');
    }
}
