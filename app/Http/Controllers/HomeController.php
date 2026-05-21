<?php

namespace App\Http\Controllers;

use App\Models\Reptile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil seluruh dokumen dari koleksi reptiles di MongoDB
        $reptiles = Reptile::orderBy('created_at', 'desc')->get();

        // Mengelompokkan kategori unik secara otomatis langsung dari data reptile yang ada
        $categories = Reptile::pluck('category')->unique()->filter();

        return view('home', compact('reptiles', 'categories'));
    }

    public function shop()
    {
        $reptiles = Reptile::all();
        $categories = Reptile::pluck('category')->unique()->filter();
        return view('shop', compact('reptiles', 'categories'));
    }

    public function detail($id)
    {
        // Mengambil satu dokumen reptile berdasarkan ID unik MongoDB
        $reptile = Reptile::findOrFail($id);
        return view('detail', compact('reptile'));
    }

    public function education()
    {
        return view('education');
    }
}