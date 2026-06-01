<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Models\Reptile;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/education', [HomeController::class, 'education'])->name('education');
Route::get('/product/{id}', [HomeController::class, 'detail'])->name('product.detail');
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/seed/{key}', function ($key) {
    if ($key !== 'anarcyx123') {
        abort(403, 'Invalid seed key');
    }

    User::truncate();
    Reptile::truncate();
    Order::truncate();
    Notification::truncate();

    User::create([
        'name' => 'Sultan',
        'email' => 'admin',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'phone_number' => '6281234567890',
        'address' => 'Jakarta, Indonesia'
    ]);

    $rep1 = Reptile::create([
        'name' => 'Rhinoceros Iguana',
        'category' => 'Iguana',
        'price' => 350000,
        'stock' => 5,
        'image' => '1716200001.jpg',
        'desc' => 'Karakter jinak khas badak, memiliki tanduk unik kecil di bagian hidung depan.',
        'attributes' => ['morph' => 'Cyclura cornuta', 'weight' => '1.5kg', 'age' => 'Juvenile']
    ]);

    $rep2 = Reptile::create([
        'name' => 'Leopard Gecko Hypo',
        'category' => 'Gecko',
        'price' => 650000,
        'stock' => 12,
        'image' => '1716200002.jpg',
        'desc' => 'Sangat cocok untuk pemula, warna kuning cerah bersih minim bintik hitam.',
        'attributes' => ['morph' => 'Super Hypo Tangerine', 'weight' => '45g', 'age' => 'Adult']
    ]);

    $rep3 = Reptile::create([
        'name' => 'Ball Python Normal',
        'category' => 'Snake',
        'price' => 1200000,
        'stock' => 3,
        'image' => '1716200003.jpg',
        'desc' => 'Ular peliharaan paling tenang di dunia, bermotif eksotis alami.',
        'attributes' => ['morph' => 'Classic Wild Type', 'weight' => '800g', 'age' => 'Sub-Adult']
    ]);

    Order::create([
        'user_id' => 'guest_user_1',
        'customer_name' => 'Rizky Ardiansyah',
        'order_id_string' => '#ORD-98214',
        'total_price' => 1550000,
        'status' => 'delivered',
        'shipping_address' => 'Jl. Merdeka No. 10, Jakarta',
        'items' => [
            ['product_id' => $rep1->id, 'name' => $rep1->name, 'qty' => 1, 'price' => $rep1->price],
            ['product_id' => $rep3->id, 'name' => $rep3->name, 'qty' => 1, 'price' => $rep3->price]
        ]
    ]);

    Order::create([
        'user_id' => 'guest_user_2',
        'customer_name' => 'Amalia Putri',
        'order_id_string' => '#ORD-98211',
        'total_price' => 650000,
        'status' => 'confirmed',
        'shipping_address' => 'Perumahan Indah B3, Bogor',
        'items' => [
            ['product_id' => $rep2->id, 'name' => $rep2->name, 'qty' => 1, 'price' => $rep2->price]
        ]
    ]);

    Notification::create([
        'type' => 'order',
        'message' => 'Pesanan baru masuk #ORD-98214 dari Rizky Ardiansyah',
        'is_read' => false,
        'created_at' => now()->subMinutes(10)->toDateTimeString()
    ]);

    Notification::create([
        'type' => 'user',
        'message' => 'Pengguna baru "Amalia Putri" telah mendaftar akun',
        'is_read' => false,
        'created_at' => now()->subHours(2)->toDateTimeString()
    ]);

    Notification::create([
        'type' => 'order',
        'message' => 'Pembayaran Invoice #INV-8812 terverifikasi valid oleh sistem',
        'is_read' => true,
        'created_at' => now()->subDays(3)->toDateTimeString()
    ]);

    Notification::create([
        'type' => 'system',
        'message' => 'Stok unit reptile "Ball Python Normal" tersisa 3 ekor',
        'is_read' => true,
        'created_at' => now()->subDays(6)->toDateTimeString()
    ]);

    return 'Database seeded successfully! 3 reptiles, admin user, orders & notifications created.';
})->where('key', '.*');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'handleLogin'])->name('admin.handleLogin');
Route::post('/admin/logout', [AdminController::class, 'handleLogout'])->name('admin.logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [AdminController::class, 'showAllNotifications'])->name('notifications');
    
    // Rute CRUD Product Management
    Route::get('/products', [AdminController::class, 'showProducts'])->name('products');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/update/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
});