<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/education', [HomeController::class, 'education'])->name('education');
Route::get('/product/{id}', [HomeController::class, 'detail'])->name('product.detail');
Route::get('/cart', function () {
    return view('cart'); // Membuat view 'cart.blade.php'
})->name('cart');

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