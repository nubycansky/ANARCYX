<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Reptile;
use App\Models\Order;
use App\Models\Notification;
use App\Models\EducationArticle;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==========================================
    // 0. ADMIN AUTH (LOGIN & LOGOUT)
    // ==========================================
    public function showLogin() {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function handleLogin(Request $request) {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $credentials['username'];

        $user = User::where('email', $identifier)
                    ->orWhere('name', $identifier)
                    ->first();

        if (!$user) {
            return back()
                ->withErrors(['login_error' => 'Username tidak terdaftar di sistem admin.'])
                ->withInput();
        }

        if ($user->role !== 'admin') {
            return back()
                ->withErrors(['login_error' => 'Akun ini bukan akun administrator.'])
                ->withInput();
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['login_error' => 'Password yang kamu masukkan salah.'])
                ->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('flash_success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function handleLogout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')
            ->with('flash_success', 'Kamu telah keluar dari panel admin.');
    }

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

        // HITUNG SALES COUNT PER PRODUK DARI ORDER ITEMS (MongoDB embedded)
        $salesByProduct = [];
        foreach (Order::all() as $order) {
            $items = $order->items ?? [];
            foreach ($items as $item) {
                $pid = (string)($item['product_id'] ?? '');
                $qty = (int)($item['qty'] ?? 0);
                if ($pid === '') continue;
                $salesByProduct[$pid] = ($salesByProduct[$pid] ?? 0) + $qty;
            }
        }

        // AMBIL TOP 5 PRODUK BERDASARKAN SALES (fallback ke stock kalau belum ada sales)
        $topProducts = Reptile::all()
            ->sortByDesc(function ($prod) use ($salesByProduct) {
                return $salesByProduct[(string)$prod->_id] ?? (int)$prod->stock;
            })
            ->take(5)
            ->map(function ($prod) use ($salesByProduct) {
                $prod->sales_count = $salesByProduct[(string)$prod->_id] ?? 0;
                return $prod;
            })
            ->values();

        $recentOrders = Order::orderBy('_id', 'desc')->take(5)->get();

        // DATA KATEGORI UNTUK PIE CHART
        $categoryData = [
            Reptile::where('category', 'Snake')->count(),
            Reptile::where('category', 'Iguana')->count(),
            Reptile::where('category', 'Gecko')->count(),
            Reptile::where('category', 'Tortoise')->count()
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'newCustomers', 
            'dropdownNotifications', 'unreadNotificationCount', 'topProducts', 'recentOrders',
            'categoryData'
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

    // ==========================================
    // 4. ORDER MANAGEMENT
    // ==========================================
    public function showOrders(Request $request) {
        $query = Order::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_id_string', 'like', '%' . $term . '%')
                  ->orWhere('customer_name', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('_id', 'desc')->get()->map(function ($order) {
            $items = $order->items ?? [];
            $order->item_count = array_sum(array_map(fn($i) => (int)($i['qty'] ?? 0), $items));
            $order->display_id = $order->order_id_string ?? ('#ORD-' . substr((string)$order->_id, -5));
            $order->display_date = $order->created_at
                ? Carbon::parse($order->created_at)->format('n/j/Y')
                : '-';
            return $order;
        });

        $stats = [
            'total'     => Order::count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'pending'   => Order::where('status', 'pending')->count(),
        ];

        $dropdownNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.orders', compact(
            'orders', 'stats',
            'dropdownNotifications', 'unreadNotificationCount'
        ));
    }

    public function updateOrderStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:pending,confirmed,delivered,cancelled',
        ]);

        $order = Order::find($id);
        if ($order) {
            $order->status = $request->status;
            $order->save();
            return redirect()->route('admin.orders')
                ->with('flash_success', 'Status pesanan ' . ($order->order_id_string ?? '#ORD-'.$id) . ' diperbarui menjadi ' . ucfirst($request->status) . '.');
        }
        return redirect()->route('admin.orders')
            ->with('flash_error', 'Pesanan tidak ditemukan.');
    }

    public function deleteOrder($id) {
        $order = Order::find($id);
        if ($order) {
            $label = $order->order_id_string ?? ('#ORD-'.$id);
            $order->delete();
            return redirect()->route('admin.orders')
                ->with('flash_success', 'Pesanan ' . $label . ' berhasil dihapus.');
        }
        return redirect()->route('admin.orders')
            ->with('flash_error', 'Pesanan tidak ditemukan.');
    }

    // ==========================================
    // 5. EDUCATION MANAGEMENT
    // ==========================================
    public function showEducation(Request $request) {
        $query = EducationArticle::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('preview', 'like', '%' . $term . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('_id', 'desc')->get();

        $stats = [
            'total'    => EducationArticle::count(),
            'general'  => EducationArticle::where('category', 'General')->count(),
            'habitat'  => EducationArticle::where('category', 'Habitat')->count(),
            'diet'     => EducationArticle::where('category', 'Diet')->count(),
        ];

        $dropdownNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        return view('admin.education', compact(
            'articles', 'stats',
            'dropdownNotifications', 'unreadNotificationCount'
        ));
    }

    public function storeArticle(Request $request) {
        $data = $request->validate([
            'title'    => 'required|string|max:200',
            'category' => 'required|in:General,Habitat,Diet,Health',
            'preview'  => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        EducationArticle::create($data);

        return redirect()->route('admin.education')
            ->with('flash_success', 'Artikel edukasi "' . $data['title'] . '" berhasil ditambahkan.');
    }

    public function updateArticle(Request $request, $id) {
        $data = $request->validate([
            'title'    => 'required|string|max:200',
            'category' => 'required|in:General,Habitat,Diet,Health',
            'preview'  => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        $article = EducationArticle::find($id);
        if ($article) {
            $article->update($data);
            return redirect()->route('admin.education')
                ->with('flash_success', 'Artikel "' . $data['title'] . '" berhasil diperbarui.');
        }
        return redirect()->route('admin.education')
            ->with('flash_error', 'Artikel tidak ditemukan.');
    }

    public function deleteArticle($id) {
        $article = EducationArticle::find($id);
        if ($article) {
            $title = $article->title;
            $article->delete();
            return redirect()->route('admin.education')
                ->with('flash_success', 'Artikel "' . $title . '" berhasil dihapus.');
        }
        return redirect()->route('admin.education')
            ->with('flash_error', 'Artikel tidak ditemukan.');
    }
}