<?php

namespace App\Http\Controllers;

use App\Models\EducationArticle;
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
        $products = Reptile::all();
        $categories = Reptile::pluck('category')->unique()->filter();
        return view('shop', compact('products', 'categories'));
    }

    public function detail($id)
    {
        // Mengambil satu dokumen reptile berdasarkan ID unik MongoDB
        $reptile = Reptile::findOrFail($id);
        return view('detail', compact('reptile'));
    }

    // 2. REVISI UTAMA: AMBIL DATA NYATA ARTIKEL DARI MONGODB ATLAS
    public function education(Request $request)
    {
        $query = EducationArticle::query();

        // Jika customer melakukan pencarian artikel
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Jika customer memfilter berdasarkan kategori edukasi (General/Diet/Habitat)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil list kategori artikel unik untuk bahan menu filter di view
        $articleCategories = EducationArticle::pluck('category')->unique()->filter();

        return view('education', compact('articles', 'articleCategories'));
    }

    public function showArticle($id)
    {
        $article = EducationArticle::findOrFail($id);
        return view('education_show', compact('article'));
    }
}