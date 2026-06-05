<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Models\Reptile;
use App\Models\Order;
use App\Models\Notification;
use App\Models\EducationArticle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/education', [HomeController::class, 'education'])->name('education');
Route::get('/education/{id}', [HomeController::class, 'showArticle'])->name('education.show');
Route::get('/products/{id}', [HomeController::class, 'detail'])->name('products.show');
Route::post('/products/{id}/review', [HomeController::class, 'storeReview'])->name('products.review');
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::view('/wishlist', 'wishlist')->name('wishlist');
Route::post('/checkout', [HomeController::class, 'submitOrder'])->name('checkout.submit');
Route::view('/order-success', 'order-success')->name('order.success');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/profile/change-password', [App\Http\Controllers\UserController::class, 'changePassword'])->name('user.profile.password');
});

// Public user authentication (customer login & signup)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('auth.handleLogin');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('auth.signup');
Route::post('/signup', [AuthController::class, 'handleSignup'])->name('auth.handleSignup');
Route::post('/logout', [AuthController::class, 'handleLogout'])->name('auth.handleLogout');

Route::get('/seed/{key}', function ($key) {
    if ($key !== 'anarcyx123') {
        abort(403, 'Invalid seed key');
    }

    User::truncate();
    Reptile::truncate();
    Order::truncate();
    Notification::truncate();
    EducationArticle::truncate();

    User::create([
        'name' => 'Sultan',
        'email' => 'admin',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'phone_number' => '62895613369443',
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

    EducationArticle::create([
        'title' => "Beginner's Guide to Reptile Care",
        'category' => 'General',
        'preview' => 'Starting with reptiles requires understanding the basics of temperature, handling, and quarantine for new units.',
        'content' => 'Panduan lengkap untuk pemula yang ingin memulai hobi reptil. Mulai dari pemilihan species, penyiapan enclosure, hingga penanganan awal hewan stres dan masa karantina unit baru. Pastikan suhu, kelembaban, dan pencahayaan UVB sesuai kebutuhan species masing-masing.'
    ]);

    EducationArticle::create([
        'title' => "Setting Up the Ideal Enclosure Temperature",
        'category' => 'Habitat',
        'preview' => 'Pentingnya pembagian area basking spot dan cool spot untuk siklus termoregulasi alami reptil.',
        'content' => 'Suhu enclosure yang ideal terbagi menjadi area berjemur (basking spot) di 32-38°C dan area dingin (cool spot) di 24-26°C. Gunakan termometer digital di kedua sisi untuk monitoring real-time, dan lampu UVB untuk membantu metabolisme kalsium.'
    ]);

    EducationArticle::create([
        'title' => 'Nutrition, Vitamin & Calcium Requirements',
        'category' => 'Diet',
        'preview' => 'Cara pemberian dusting suplemen bubuk yang benar pada serangga pakan agar terhindari dari MBD.',
        'content' => 'Dusting adalah teknik membaluri serangga pakan dengan bubuk kalsium atau vitamin. Lakukan 2-3x seminggu untuk juvenile dan 1x seminggu untuk adult. Pastikan rasio kalsium:fosphor seimbang untuk mencegah Metabolic Bone Disease (MBD).'
    ]);

    EducationArticle::create([
        'title' => 'Recognizing Signs of Illness in Reptiles',
        'category' => 'Health',
        'preview' => 'Deteksi awal gejala infeksi saluran pernapasan (RI), mogok makan, dan masalah pencernaan.',
        'content' => 'Tanda reptil sakit antara lain: lendir berlebih di mulut/hidung (respiratory infection), lesu, tidak mau makan >2 minggu, feses abnormal, dan perubahan warna kulit. Segera konsultasi ke dokter hewan reptil terdekat jika menemukan tanda-tanda ini.'
    ]);

    return 'Database seeded successfully! 3 reptiles, admin user, orders, notifications & 4 education articles created.';
})->where('key', '.*');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'handleLogin'])->name('admin.handleLogin');
Route::post('/admin/logout', [AdminController::class, 'handleLogout'])->name('admin.logout');

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [AdminController::class, 'showAllNotifications'])->name('notifications');
    Route::delete('/notifications/clear', [AdminController::class, 'clearNotifications'])->name('notifications.clear');
    Route::delete('/notifications/destroy/{id}', [AdminController::class, 'destroyNotification'])->name('notifications.destroy');
    
    // Rute CRUD Product Management
    Route::get('/products', [AdminController::class, 'showProducts'])->name('products');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/update/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // Rute Order Management
    Route::get('/orders', [AdminController::class, 'showOrders'])->name('orders');
    Route::post('/orders/update-status/{id}', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::post('/orders/approve/{id}', [AdminController::class, 'approve'])->name('orders.approve');
    Route::post('/orders/reject/{id}', [AdminController::class, 'reject'])->name('orders.reject');
    Route::delete('/orders/delete/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
    Route::get('/orders/invoice/{id}', [AdminController::class, 'generateInvoicePdf'])->name('orders.invoice');

    // Rute Education Management
    Route::get('/education', [AdminController::class, 'showEducation'])->name('education');
    Route::post('/education/store', [AdminController::class, 'storeArticle'])->name('education.store');
    Route::post('/education/update/{id}', [AdminController::class, 'updateArticle'])->name('education.update');
    Route::delete('/education/delete/{id}', [AdminController::class, 'deleteArticle'])->name('education.delete');
});