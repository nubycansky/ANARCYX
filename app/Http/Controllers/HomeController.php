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
            'shipping_cost'    => 'nullable|numeric|min:0',
            'items'            => 'required|array|min:1',
            'items.*.product_id'   => 'nullable|string',
            'items.*.product_name' => 'required|string',
            'items.*.qty'          => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $customerName = $user->name;
            $customerPhone = $user->phone_number ?? $request->customer_phone;
            $customerAddress = $user->address ?? $request->customer_address;
        } else {
            $customerName = $request->customer_name ?? 'Guest User';
            $customerPhone = $request->customer_phone ?? '-';
            $customerAddress = $request->customer_address ?? '';
        }

        $items = [];
        $subtotal = 0;
        foreach ($request->items as $item) {
            $product = \App\Models\Reptile::find($item['product_id']);
            $realPrice = $product ? (int)$product->price : (int)($item['price'] ?? 0);
            $qty = (int)$item['qty'];
            $items[] = [
                'product_id'   => $item['product_id'] ?? '',
                'product_name' => $item['product_name'],
                'qty'          => $qty,
                'price'        => $realPrice,
            ];
            $subtotal += $realPrice * $qty;
        }

        $shippingCost = (int)($request->shipping_cost ?? 20000);
        $totalAmount = $subtotal + $shippingCost;

        $order = new Order();
        $order->user_id = Auth::check() ? Auth::id() : 'guest_' . uniqid();
        $order->customer_name = $customerName;
        $order->customer_phone = $customerPhone;
        $order->customer_address = $customerAddress;
        $order->order_id_string = '#ORD-' . strtoupper(substr(uniqid(), -6));
        $order->subtotal = $subtotal;
        $order->shipping_cost = $shippingCost;
        $order->total_amount = $totalAmount;
        $order->total_price = $totalAmount;
        $order->status = 'pending';
        $order->items = $items;
        $order->save();

        Notification::create([
            'type' => 'order',
            'message' => '?? Pesanan baru masuk ' . $order->order_id_string . ' dari ' . $order->customer_name,
            'created_at' => now()
        ]);

        return response()->json(['success' => true, 'order_id' => (string)$order->_id]);
    }

    public function quickWa(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'price'        => 'required|numeric',
            'product_id'   => 'nullable|string',
            'qty'          => 'nullable|integer|min:1',
        ]);

        $qty = $request->qty ?? 1;

        if (Auth::check()) {
            $user = Auth::user();
            $customerName = $user->name;
            $customerPhone = $user->phone_number ?? '-';
        } else {
            $customerName = 'Guest User';
            $customerPhone = '-';
        }

        $realPrice = (int)$request->price;
        if ($request->product_id) {
            $dbProduct = \App\Models\Reptile::find($request->product_id);
            if ($dbProduct) $realPrice = (int)$dbProduct->price;
        }
        $itemTotal = $realPrice * $qty;

        $order = new Order();
        $order->user_id = Auth::check() ? Auth::id() : 'guest_' . uniqid();
        $order->customer_name = $customerName;
        $order->customer_phone = $customerPhone;
        $order->order_id_string = '#ORD-' . strtoupper(substr(uniqid(), -6));
        $order->subtotal = $itemTotal;
        $order->shipping_cost = 0;
        $order->total_amount = $itemTotal;
        $order->total_price = $itemTotal;
        $order->status = 'pending';
        $order->items = [[
            'product_id'   => $request->product_id ?? '',
            'product_name' => $request->product_name,
            'qty'          => $qty,
            'price'        => $realPrice,
        ]];
        $order->save();

        Notification::create([
            'type' => 'order',
            'message' => '?? Pesanan cepat masuk ' . $order->order_id_string . ' dari ' . $customerName,
            'created_at' => now()
        ]);

        $formattedPrice = number_format((int)$request->price, 0, ',', '.');
        $totalFormatted = number_format((int)$request->price * $qty, 0, ',', '.');
        $textMessage = "Halo AnarcyxReptile, saya ingin memesan unit ini:\n\n"
                     . "• *Nama Unit:* {$request->product_name}\n"
                     . "• *Harga:* Rp.{$formattedPrice}\n"
                     . "• *Qty:* {$qty}\n"
                     . "• *Total:* Rp.{$totalFormatted}\n\n"
                     . "Mohon dibantu infokan langkah pembayarannya. Terima kasih!";

        $waUrl = 'https://wa.me/62895613369443?text=' . urlencode($textMessage);

        return response()->json([
            'success' => true,
            'wa_url'  => $waUrl,
            'order_id' => (string)$order->_id,
        ]);
    }

}