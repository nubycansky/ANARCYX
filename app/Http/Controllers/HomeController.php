<?php

namespace App\Http\Controllers;

use App\Models\EducationArticle;
use App\Models\Reptile;
use App\Models\Review;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $reptile = Reptile::findOrFail($id);
        $reviews = Review::where('product_id', $id)->orderBy('created_at', 'desc')->paginate(4);
        $averageRating = Review::where('product_id', $id)->avg('rating') ?? 0;
        $totalReviews = Review::where('product_id', $id)->count();
        return view('detail', compact('reptile', 'reviews', 'averageRating', 'totalReviews'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:200',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:2000',
        ]);

        Review::create([
            'product_id'    => $id,
            'customer_name' => $request->customer_name,
            'rating'        => (int)$request->rating,
            'comment'       => $request->comment,
            'created_at'    => now(),
        ]);

        return redirect()->back()->with('review_success', 'Ulasan Anda berhasil dikirim! Terima kasih atas partisipasinya.');
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
    public function submitOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:200',
            'customer_phone'   => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:500',
            'total_price'      => 'required|numeric',
            'items'            => 'required|array|min:1',
            'items.*.product_id'   => 'nullable|string',
            'items.*.product_name' => 'required|string',
            'items.*.qty'          => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric',
        ]);

        $order = new Order();
        $order->user_id = 'guest_' . uniqid();
        $order->customer_name = $request->customer_name;
        $order->customer_phone = $request->customer_phone;
        $order->customer_address = $request->customer_address;
        $order->order_id_string = '#ORD-' . strtoupper(substr(uniqid(), -6));
        $order->total_price = $request->total_price;
        $order->status = 'pending';
        $order->items = $request->items;
        $order->save();

        Notification::create([
            'type' => 'order',
            'message' => '?? Pesanan baru masuk ' . $order->order_id_string . ' dari ' . $order->customer_name,
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'order_id' => (string)$order->_id]);
    }

}