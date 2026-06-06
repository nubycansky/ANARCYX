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
use App\Models\Invoice;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==========================================
    // 0. ADMIN AUTH (LOGIN & LOGOUT)
    // ==========================================
    public function showLogin() {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function handleLogin(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = ['email' => $request->username, 'password' => $request->password];

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            if ($user->role !== 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors(['login_error' => 'Username atau password salah'])->withInput();
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('flash_success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors(['login_error' => 'Username atau password salah'])->withInput();
    }

    public function handleLogout(Request $request) {
        Auth::guard('admin')->logout();
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
        $inStockQty = Reptile::where('stock', '>', 0)->count();
        $outStockQty = Reptile::where('stock', '<=', 0)->count();

        $totalValueAsset = 0;
        foreach (Reptile::all() as $rep) {
            $totalValueAsset += ((int)$rep->price * (int)$rep->stock);
        }

        $dropdownNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
        $unreadNotificationCount = Notification::where('is_read', false)->count();

        // SALES COUNT PER PRODUCT DARI ORDER ITEMS (MongoDB embedded)
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

        // TOP 5 PRODUCTS BASED ON SALES
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

        // PENDING ORDERS (menunggu konfirmasi)
        $pendingOrders = Order::where('status', 'pending')->orderBy('created_at', 'desc')->get();

        // CATEGORY DATA — "kadal" maps to Gecko
        $snakeCount = Reptile::where('category', 'Snake')->count();
        $iguanaCount = Reptile::where('category', 'Iguana')->count();
        $geckoCount = Reptile::where('category', 'Gecko')->count()
                    + Reptile::where('category', 'kadal')->count();
        $tortoiseCount = Reptile::where('category', 'Tortoise')->count();
        $categoryData = [$snakeCount, $iguanaCount, $geckoCount, $tortoiseCount];

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'newCustomers',
            'inStockQty', 'outStockQty', 'totalValueAsset',
            'dropdownNotifications', 'unreadNotificationCount', 'topProducts', 'recentOrders',
            'pendingOrders', 'categoryData'
        ));
    }

    public function pendingOrdersApi()
    {
        $pending = Order::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($o) {
                return [
                    '_id'            => (string)$o->_id,
                    'order_number'   => $o->order_number ?? substr((string)$o->_id, 0, 8),
                    'order_id_string'=> $o->order_id_string ?? '#ORD-' . substr((string)$o->_id, -5),
                    'customer_name'  => $o->customer_name,
                    'customer_phone' => $o->customer_phone ?? '-',
                    'total_price'    => (int)($o->total_amount ?? $o->total_price ?? 0),
                    'created_at'     => (string)$o->created_at,
                    'approve_url'    => route('admin.orders.approve', $o->_id),
                    'reject_url'     => route('admin.orders.reject', $o->_id),
                ];
            });

        return response()->json(['orders' => $pending]);
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
            'description' => $request->desc,
            'short_description' => $request->short_description,
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
            'desc' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $product = Reptile::find($id);
        $product->name = $request->name;
        $product->category = $request->category;
        $product->price = (int)$request->price;
        $product->stock = (int)$request->stock;
        $product->desc = $request->desc;
        $product->description = $request->desc;
        $product->short_description = $request->short_description;

        if ($request->hasFile('image')) {
            if($product->image && $product->image != 'default.jpg') {
                @unlink(public_path('images/products/'.$product->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        if ($product->stock <= 3) {
            \App\Models\Notification::create([
                'type' => 'system_stock',
                'message' => '⚠️ Stok unit reptile "' . $product->name . '" tersisa ' . $product->stock . ' ekor!',
                'created_at' => now()
            ]);
        }

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

    public function clearNotifications()
    {
        \App\Models\Notification::truncate();

        return redirect()->back()->with('flash_success', 'Seluruh riwayat notifikasi berhasil dibersihkan!');
    }

    public function destroyNotification($id)
    {
        $notification = \App\Models\Notification::find($id);
        if ($notification) {
            $notification->delete();
        }
        return redirect()->back()->with('flash_success', 'Notifikasi berhasil dihapus.');
    }

    // ==========================================
    // 4. ORDER MANAGEMENT
    // ==========================================
    public function showOrders(Request $request) {
        $query = Order::query();
        $query->where('status', '!=', 'pending');

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

        $orders = $query->orderBy('created_at', 'desc')->get()->map(function ($order) {
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
            $oldStatus = $order->status;
            $newStatus = $request->status;
            $order->status = $newStatus;
            $order->save();

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                if (!empty($order->items) && count($order->items) > 0) {
                    foreach ($order->items as $item) {
                        $productId = $item['product_id'] ?? $item['id'] ?? null;
                        if ($productId) {
                            $product = \App\Models\Reptile::find($productId);
                            if ($product) {
                                $product->increment('stock', intval($item['qty']));
                            }
                        }
                    }
                }
            }

            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                if (!empty($order->items) && count($order->items) > 0) {
                    foreach ($order->items as $item) {
                        $productId = $item['product_id'] ?? $item['id'] ?? null;
                        if ($productId) {
                            $product = \App\Models\Reptile::find($productId);
                            if ($product) {
                                $product->decrement('stock', intval($item['qty']));
                            }
                        }
                    }
                }
            }

            if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                if (!empty($order->items) && is_array($order->items)) {
                    $stockWarnings = [];
                    foreach ($order->items as $item) {
                        $productId = $item['product_id'] ?? $item['id'] ?? null;
                        $qty = (int)($item['qty'] ?? 1);
                        if ($productId) {
                            $product = \App\Models\Reptile::find($productId);
                            if ($product) {
                                if ($product->stock < $qty) {
                                    $stockWarnings[] = $product->name . ' (stok: ' . $product->stock . ', diminta: ' . $qty . ')';
                                }
                                $product->decrement('stock', $qty);
                                if ($product->stock < 0) {
                                    $product->stock = 0;
                                    $product->save();
                                }
                            }
                        }
                    }
                    if (!empty($stockWarnings)) {
                        $warningMsg = 'Stok tidak mencukupi untuk: ' . implode(', ', $stockWarnings) . '. Stok telah diset ke 0.';
                        \Illuminate\Support\Facades\Session::flash('flash_warning', $warningMsg);
                    }
                }

                \App\Models\Invoice::create([
                    'order_id' => $id,
                    'order_id_string' => $order->order_id_string ?? '#ORD-' . $id,
                    'customer_name' => $order->customer_name,
                    'total_price' => $order->total_price,
                    'payment_status' => 'pending',
                ]);

                \App\Models\Notification::create([
                    'type' => 'order',
                    'message' => '🛒 Pesanan baru masuk #' . ($order->order_id_string ?? '#ORD-'.$id) . ' dari ' . $order->customer_name,
                    'created_at' => now()
                ]);
            }

            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                \App\Models\Notification::create([
                    'type' => 'invoice',
                    'message' => '💳 Pembayaran Invoice #' . ($order->order_id_string ?? '#ORD-'.$id) . ' terverifikasi valid oleh sistem.',
                    'created_at' => now()
                ]);
            }

            return redirect()->route('admin.orders')
                ->with('flash_success', 'Status pesanan ' . ($order->order_id_string ?? '#ORD-'.$id) . ' diperbarui menjadi ' . ucfirst($request->status) . '.');
        }
        return redirect()->route('admin.orders')
            ->with('flash_error', 'Pesanan tidak ditemukan.');
    }

    public function approve($id) {
        $order = Order::find($id);
        if ($order) {
            $request = new Request(['status' => 'confirmed']);
            return app()->call('App\Http\Controllers\AdminController@updateOrderStatus', [
                'request' => $request,
                'id' => $id
            ]);
        }
        return redirect()->route('admin.orders')
            ->with('flash_error', 'Pesanan tidak ditemukan.');
    }

    public function reject($id) {
        $order = Order::find($id);
        if ($order) {
            $label = $order->order_id_string ?? ('#ORD-'.$id);
            $order->delete();
            return redirect()->back()
                ->with('flash_success', 'Pesanan ' . $label . ' telah ditolak dan dihapus dari sistem.');
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
            'image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $article = new EducationArticle();
        $article->title = $request->title;
        $article->category = $request->category;
        $article->preview = $request->preview;
        $article->content = $request->content;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/education'), $filename);
            $article->image = $filename;
        }

        $article->save();

        return redirect()->route('admin.education')
            ->with('flash_success', 'Artikel edukasi "' . $data['title'] . '" berhasil ditambahkan.');
    }

    public function updateArticle(Request $request, $id) {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'category'    => 'required|in:General,Habitat,Diet,Health',
            'preview'     => 'required|string|max:255',
            'content'     => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_image' => 'nullable|in:0,1',
        ]);

        $article = EducationArticle::find($id);
        if ($article) {
            $article->title = $request->title;
            $article->category = $request->category;
            $article->preview = $request->preview;
            $article->content = $request->content;

            if ($request->delete_image == "1") {
                if (!empty($article->image) && file_exists(public_path('images/education/' . $article->image))) {
                    @unlink(public_path('images/education/' . $article->image));
                }
                $article->image = null;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/education'), $filename);
                $article->image = $filename;
            }

            $article->save();
            return redirect()->route('admin.education')
                ->with('flash_success', 'Artikel "' . $data['title'] . '" berhasil diperbarui.');
        }
        return redirect()->route('admin.education')
            ->with('flash_error', 'Artikel tidak ditemukan.');
    }

    public function generateInvoicePdf($id) {
        $order = \App\Models\Order::find($id);
        if (!$order) {
            return redirect()->route('admin.orders')
                ->with('flash_error', 'Pesanan tidak ditemukan.');
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoice_pdf', compact('order'));
        $filename = 'invoice-' . ($order->order_id_string ?? substr($order->_id, 0, 8)) . '.pdf';
        return $pdf->download($filename);
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