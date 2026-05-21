<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Reptile;
use App\Models\Order;
use App\Models\Notification;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD OVERVIEW
    // ==========================================
    // ==========================================
    // 1. DASHBOARD UTAMA (100% LIVE DATABASE MONGODB)
    // ==========================================
    public function index() {
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price') ?: 0;
        $totalOrders = Order::count() ?: 0;
        $totalProducts = Reptile::count() ?: 0;
        $newCustomers = User::where('role', 'customer')->count() ?: 0;

        $dropdownNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        $topProducts = Reptile::orderBy('stock', 'asc')->take(5)->get();
        $recentOrders = Order::orderBy('_id', 'desc')->take(5)->get();

        // HITUNG DATA KATEGORI REPTIL DI SINI (AGAR JAVASCRIPT BERSIH DARI BLADE)
        $categoryData = [
            Reptile::where('category', 'Snake')->count(),
            Reptile::where('category', 'Iguana')->count(),
            Reptile::where('category', 'Gecko')->count(),
            Reptile::where('category', 'Tortoise')->count()
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'newCustomers', 
            'dropdownNotifications', 'unreadNotificationCount', 'topProducts', 'recentOrders',
            'categoryData' // Kirim data array ke view
        ));
    }

    // ==========================================
    // 2. PAGE MANAGEMENT PRODUK (LIVE METRIK & VALUE ASSET)
    // ==========================================
    public function showProducts(Request $request) {
        $query = Reptile::query();

        // Fitur Live Search & Filter Kategori MongoDB Compass
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('category') && $request->category != 'All Categories') {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('_id', 'desc')->get();

        // Kalkulasi Statistik Atas Berdasarkan Data Nyata
        $totalQty = Reptile::count();
        $inStockQty = Reptile::where('stock', '>', 0)->count();
        $outStockQty = Reptile::where('stock', '<=', 0)->count();
        
        // Menghitung Nilai Aset: Akumulasi (Harga Satuan x Jumlah Stok) dari MongoDB
        $totalValue = 0;
        $allReptiles = Reptile::all();
        foreach ($allReptiles as $rep) {
            $totalValue += ((int)$rep->price * (int)$rep->stock);
        }

        // Ambil data notifikasi untuk navbar page produk
        $dropdownNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.products', compact(
            'products', 'totalQty', 'inStockQty', 'outStockQty', 'totalValue',
            'dropdownNotifications', 'unreadNotificationCount'
        ));
    }

    public function storeProduct(Request $request) {
        // Validasi Ketat Sisi Server
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'desc' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageName = 'default.jpg';
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
        }

        Reptile::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => (int)$request->price,
            'stock' => (int)$request->stock,
            'desc' => $request->desc,
            'image' => $imageName,
            'attributes' => [
                'morph' => $request->morph ?? 'Normal morph',
                'weight' => 'Unknown',
                'age' => 'Baby'
            ]
        ]);

        return redirect()->route('admin.products')->with('flash_success', 'Data Berhasil Ditambahkan!');
    }

    public function updateProduct(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'desc' => 'required'
        ]);

        $product = Reptile::find($id);
        $product->name = $request->name;
        $product->category = $request->category;
        $product->price = (int)$request->price;
        $product->stock = (int)$request->stock;
        $product->desc = $request->desc;

        if ($request->hasFile('image')) {
            if($product->image && $product->image != 'default.jpg') {
                @unlink(public_path('images/products/'.$product->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();
        return redirect()->route('admin.products')->with('flash_success', 'Perubahan Data Berhasil Disimpan!');
    }

    public function deleteProduct($id) {
        $product = Reptile::find($id);
        if($product) {
            if($product->image && $product->image != 'default.jpg') {
                @unlink(public_path('images/products/'.$product->image));
            }
            $product->delete();
            return redirect()->route('admin.products')->with('flash_success', 'Data Berhasil Dihapus!');
        }
        return redirect()->route('admin.products')->with('flash_error', 'Gagal Menghapus Data');
    }

    public function showAllNotifications() {
        Notification::where('is_read', false)->update(['is_read' => true]);
        $allNotifications = Notification::orderBy('created_at', 'desc')->get();
        $recentNotifications = [];
        $lastWeekNotifications = [];

        foreach ($allNotifications as $noti) {
            $createdAt = Carbon::parse($noti->created_at);
            if ($createdAt->isToday()) {
                $recentNotifications[] = $noti;
            } else {
                $lastWeekNotifications[] = $noti;
            }
        }
        return view('admin.notifications', compact('recentNotifications', 'lastWeekNotifications'));
    }
}