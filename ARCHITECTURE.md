# Arsitektur Sistem ANARCYX (AnarcyxReptile)

> Dokumen arsitektur resmi untuk aplikasi e-commerce reptil eksotis **ANARCYX**.
> Dibangun dengan Laravel 13 + MongoDB (NoSQL Document Database).

---

## 1. Ringkasan Sistem

**ANARCYX** adalah aplikasi web e-commerce khusus reptil eksotis yang menyediakan dua
pelayanan utama:

| Area | Fungsi |
|---|---|
| **Public Storefront** | Katalog produk, detail reptil, edukasi, keranjang (cart) |
| **Admin Dashboard** | Manajemen produk (CRUD), monitoring pesanan, notifikasi, statistik penjualan |

Tujuan arsitektural:

1. **Skalabel** — memanfaatkan MongoDB untuk embedding data yang fleksibel (mis. `items` di dalam dokumen `orders`).
2. **Maintainable** — mengikuti pola MVC Laravel dengan pemisahan Model, View, Controller yang jelas.
3. **Aman** — Autentikasi admin berbasis session Laravel + middleware `auth`.
4. **Cepat dikembangkan** — Vite + Tailwind CSS 4 untuk iterasi UI yang ringan.

---

## 2. Tech Stack

```
┌────────────────────────────────────────────────────────────┐
│                      FRONTEND (View)                       │
│   Blade Template  ·  Tailwind CSS 4  ·  Vanilla JS         │
│   (Vite 8 sebagai asset bundler)                           │
└────────────────────────────────────────────────────────────┘
                           │ HTTP
┌────────────────────────────────────────────────────────────┐
│                  WEB SERVER & APPLICATION                  │
│   PHP 8.3+  ·  Laravel Framework 13.8                      │
│   (Routing, Middleware, Session, Auth, Validation)         │
└────────────────────────────────────────────────────────────┘
                           │ Eloquent / Query Builder
┌────────────────────────────────────────────────────────────┐
│               DATABASE LAYER (NoSQL Document)              │
│   MongoDB 7.x  ·  mongodb/laravel-mongodb v5.7             │
│   Database: db_anarcyxreptile                              │
└────────────────────────────────────────────────────────────┘
                           │
┌────────────────────────────────────────────────────────────┐
│                  STORAGE & AUXILIARY                       │
│   Local Filesystem (public/images/products)  ·  Logs       │
│   Queue: database  ·  Cache: database  ·  Session: database│
└────────────────────────────────────────────────────────────┘
```

**Composer dependencies** (`composer.json`):

| Paket | Versi | Peran |
|---|---|---|
| `laravel/framework` | ^13.8 | Core framework |
| `mongodb/laravel-mongodb` | ^5.7 | Eloquent driver untuk MongoDB |
| `laravel/tinker` | ^3.0 | REPL untuk debugging |
| `phpunit/phpunit` (dev) | ^12.5 | Unit & feature testing |
| `laravel/pint` (dev) | ^1.27 | Code style fixer |

**NPM dependencies** (`package.json`):

| Paket | Peran |
|---|---|
| `vite` ^8.0.0 | Dev server & build tool |
| `tailwindcss` ^4.0.0 | Utility-first CSS |
| `@tailwindcss/vite` | Integrasi Tailwind ke Vite |
| `laravel-vite-plugin` ^3.1 | Integrasi Vite ke Laravel |
| `concurrently` (dev) | Menjalankan multiple watcher bersamaan |

---

## 3. Pola Arsitektur: Layered MVC + Service-Ready

```
┌──────────────────────────────────────────────────────────────┐
│  PRESENTATION LAYER  (resources/views/*.blade.php)          │
│  ─ Layout, partials, komponen UI statis & dinamis            │
└──────────────────────────────────────────────────────────────┘
                              ▲  render()
┌──────────────────────────────────────────────────────────────┐
│  CONTROLLER LAYER  (app/Http/Controllers)                   │
│  ─ HomeController (public)                                   │
│  ─ AdminController (admin)                                   │
│  Tanggung jawab: terima Request → panggil Model → return    │
│  Response/view. TIDAK berisi business logic berat.           │
└──────────────────────────────────────────────────────────────┘
                              ▲  Eloquent query
┌──────────────────────────────────────────────────────────────┐
│  MODEL LAYER  (app/Models)                                   │
│  ─ User, Reptile, Order, Invoice, Notification              │
│  Extends MongoDB\Laravel\Eloquent\Model                     │
│  Mendefinisikan: $connection, $collection, $fillable        │
└──────────────────────────────────────────────────────────────┘
                              ▲  MongoDB Driver
┌──────────────────────────────────────────────────────────────┐
│  PERSISTENCE LAYER  (MongoDB Server)                         │
│  Collections: users, reptiles, orders, invoices,            │
│               notifications, sessions, cache, jobs           │
└──────────────────────────────────────────────────────────────┘
```

**Aliran request tipikal (contoh: buka halaman Shop):**

```
Browser → /shop
   → public/index.php
   → routes/web.php  (Route::get('/shop', ...))
   → HomeController@shop
   → Reptile::all()             ← Model query
   → return view('shop', ...)   ← Blade render
   → HTML response ke browser
```

---

## 4. Struktur Direktori Proyek

```
ANARCYX/
├── app/
│   ├── Http/Controllers/
│   │   ├── Controller.php           ← base controller
│   │   ├── HomeController.php       ← public: home, shop, detail, education
│   │   └── AdminController.php      ← admin: login, dashboard, products CRUD
│   ├── Models/
│   │   ├── User.php                 ← Authenticatable (MongoDB)
│   │   ├── Reptile.php              ← produk reptil
│   │   ├── Order.php                ← pesanan (dengan embedded items[])
│   │   ├── Invoice.php              ← bukti pembayaran
│   │   └── Notification.php         ← notifikasi admin
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/                       ← bootstrapping framework
├── config/
│   ├── app.php · auth.php · cache.php · database.php
│   ├── filesystems.php · logging.php · mail.php
│   ├── queue.php · services.php · session.php
├── database/
│   ├── migrations/                  ← struktur awal koleksi Mongo
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_05_19_070523_create_reptiles_table.php
│   │   ├── 2026_05_19_074321_create_orders_table.php
│   │   ├── 2026_05_19_074327_create_order_items_table.php
│   │   └── 2026_05_19_074332_create_invoices_table.php
│   ├── factories/UserFactory.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── MongoDBSeeder.php        ← seeder khusus data Mongo
├── public/
│   ├── index.php                    ← entry point
│   ├── .htaccess · robots.txt
│   ├── css/style.css
│   └── images/products/             ← upload gambar produk
├── resources/
│   ├── css/app.css · js/app.js
│   └── views/
│       ├── home.blade.php · shop.blade.php · detail.blade.php
│       ├── cart.blade.php · education.blade.php · welcome.blade.php
│       └── admin/
│           ├── login.blade.php
│           ├── dashboard.blade.php
│           ├── products.blade.php
│           └── notifications.blade.php
├── routes/
│   ├── web.php                      ← semua route web
│   └── console.php
├── storage/
│   ├── app/ · framework/ · logs/
├── tests/                           ← PHPUnit / Pest
├── .env · .env.example
├── artisan · composer.json · package.json
└── vite.config.js · phpunit.xml
```

---

## 5. Pemetaan Route

| Method | URI | Name | Controller@Method | Akses |
|---|---|---|---|---|
| GET | `/` | `home` | HomeController@index | Publik |
| GET | `/shop` | `shop` | HomeController@shop | Publik |
| GET | `/education` | `education` | HomeController@education | Publik |
| GET | `/product/{id}` | `product.detail` | HomeController@detail | Publik |
| GET | `/cart` | `cart` | Closure (view only) | Publik |
| GET | `/admin/login` | `admin.login` | AdminController@showLogin | Publik |
| POST | `/admin/login` | `admin.handleLogin` | AdminController@handleLogin | Publik |
| POST | `/admin/logout` | `admin.logout` | AdminController@handleLogout | Publik |
| GET | `/admin/dashboard` | `admin.dashboard` | AdminController@index | **auth** |
| GET | `/admin/notifications` | `admin.notifications` | AdminController@showAllNotifications | **auth** |
| GET | `/admin/products` | `admin.products` | AdminController@showProducts | **auth** |
| POST | `/admin/products/store` | `admin.products.store` | AdminController@storeProduct | **auth** |
| POST | `/admin/products/update/{id}` | `admin.products.update` | AdminController@updateProduct | **auth** |
| DELETE | `/admin/products/delete/{id}` | `admin.products.delete` | AdminController@deleteProduct | **auth** |

Middleware `auth` dipasang melalui group `Route::middleware(['auth'])->prefix('admin')`.

---

## 6. Skema Data (MongoDB — `db_anarcyxreptile`)

Karena MongoDB bersifat **schema-less**, struktur di bawah adalah representasi logis
dari dokumen yang disimpan. File migration di Laravel hanya membuat kerangka minimal
(`_id` + `created_at` + `updated_at`) — bentuk final dokumen ditentukan oleh aplikasi
melalui Eloquent Model.

### 6.1 Collection `users`
```json
{
  "_id": "ObjectId",
  "name": "string",
  "email": "string (unique)",
  "password": "bcrypt-hash",
  "role": "admin | customer",
  "phone_number": "string",
  "address": "string",
  "remember_token": "string",
  "email_verified_at": "datetime",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 6.2 Collection `reptiles`
```json
{
  "_id": "ObjectId",
  "name": "string",
  "category": "Snake | Iguana | Gecko | Tortoise",
  "price": "int (rupiah)",
  "stock": "int",
  "image": "string (filename)",
  "desc": "string",
  "attributes": {
    "morph": "string",
    "weight": "string",
    "age": "Baby | Juvenile | Sub-Adult | Adult"
  },
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 6.3 Collection `orders` (dengan **embedding** `items`)
```json
{
  "_id": "ObjectId",
  "user_id": "string | ObjectId",
  "customer_name": "string",
  "order_id_string": "string (#ORD-xxxxx)",
  "total_price": "int",
  "status": "pending | confirmed | shipped | delivered | cancelled",
  "shipping_address": "string",
  "items": [
    {
      "product_id": "ObjectId",
      "name": "string",
      "qty": "int",
      "price": "int"
    }
  ],
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 6.4 Collection `invoices`
```json
{
  "_id": "ObjectId",
  "order_id": "ObjectId (ref orders)",
  "payment_method": "string (Transfer BCA / Mandiri / dll)",
  "transfer_proof": "string (filename)",
  "amount_paid": "int",
  "payment_status": "pending | valid | invalid",
  "paid_at": "datetime",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 6.5 Collection `notifications`
```json
{
  "_id": "ObjectId",
  "type": "order | user | system",
  "message": "string",
  "is_read": "bool",
  "created_at": "datetime"
}
```

### 6.6 Relasi Logis (tanpa JOIN di MongoDB)
```
User (1) ─────< Order (N)   (embed: items[])
Order (1) ────(1:1)──── Invoice
Admin ─────────< Notification (satu koleksi global, bisa ditambah field recipient_id)
```

> **Catatan desain:** karena `items` di-embed ke dalam `orders`, pembacaan satu order
> + semua itemnya hanya butuh **1 query** (performa tinggi). Trade-off: update item
> harus menyentuh dokumen order. Cocok untuk use-case e-commerce skala kecil-menengah.

---

## 7. Use Case & Alur Bisnis

### 7.1 Public Storefront

```
[Pelanggan]
   │
   ├──► (1) Browse Home  → GET /            → tampil produk terbaru
   ├──► (2) Browse Shop  → GET /shop        → tampil semua produk + filter kategori
   ├──► (3) Lihat Detail → GET /product/{id}→ deskripsi, gambar, harga, atribut
   ├──► (4) Baca Edukasi → GET /education   → konten statis
   └──► (5) Keranjang    → GET /cart        → view (belum ada aksi persisten)
```

### 7.2 Admin Panel

```
[Admin]
   │
   ├──► Login            → POST /admin/login
   │      └─ validasi → set session (Laravel Auth)
   │
   ├──► Dashboard        → GET /admin/dashboard
   │      └─ agregasi: total revenue, orders, products, customers
   │      └─ chart data: kategori produk (Snake/Iguana/Gecko/Tortoise)
   │      └─ 5 pesanan & notifikasi terbaru
   │
   ├──► Products (CRUD)  → GET    /admin/products
   │                      POST   /admin/products/store
   │                      POST   /admin/products/update/{id}
   │                      DELETE /admin/products/delete/{id}
   │      └─ validasi server-side (required, numeric, image max 2MB)
   │      └─ upload gambar ke public/images/products/
   │
   ├──► Notifications    → GET /admin/notifications
   │      └─ tandai semua unread → read
   │      └─ pisahkan "hari ini" vs "minggu lalu"
   │
   └──► Logout           → POST /admin/logout
```

### 7.3 Alur Checkout (high-level, untuk ekstensi selanjutnya)
```
Cart → Checkout Form → Create Order (pending)
   → Upload Bukti Transfer → Create Invoice (pending)
   → Admin Verifikasi Invoice → Update Invoice.status = valid
   → Update Order.status → confirmed → shipped → delivered
   → Trigger Notification ke admin
```

---

## 8. Arsitektur Keamanan

| Lapisan | Mekanisme |
|---|---|
| **Autentikasi** | Laravel `Auth` + `MongoDB\Laravel\Auth\User` (password di-hash bcrypt, BCRYPT_ROUNDS=12) |
| **Otorisasi** | Middleware `auth` pada group route `/admin/*` |
| **CSRF** | Otomatis oleh Laravel untuk semua POST/DELETE |
| **Validasi Input** | `$request->validate([...])` di setiap endpoint mutasi |
| **Mass Assignment** | `$fillable` di setiap Model — hanya field yang diizinkan |
| **Password Hashing** | Cast `password => hashed` di Model `User` |
| **Upload File** | Validasi `mimes:jpeg,png,jpg`, max 2048 KB, disimpan di `public/images/products/` |
| **Session** | `SESSION_DRIVER=database` (disimpan di koleksi `sessions` MongoDB) |
| **APP_DEBUG** | Set `false` di production untuk mencegah kebocoran stacktrace |
| **APP_KEY** | Dibuat via `php artisan key:generate`, wajib di-rotate untuk production |

---

## 9. Konfigurasi Environment (`.env`)

```
APP_NAME=Laravel
APP_ENV=local              ← set "production" saat deploy
APP_DEBUG=true             ← set "false" saat deploy
APP_URL=http://localhost

DB_CONNECTION=mongodb
DB_URI=mongodb://127.0.0.1:27017/
DB_DATABASE=db_anarcyxreptile

SESSION_DRIVER=database     ← session, cache, queue pakai MongoDB
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log             ← hanya untuk dev; ganti ke smtp/ses di production
```

> **Rekomendasi production:** gunakan **Redis** untuk cache & queue,
> **S3** untuk filesystem, dan **SES/Mailgun** untuk mailer.

---

## 10. Arsitektur Deployment (rekomendasi)

```
                          ┌────────────────────┐
                          │   Cloudflare CDN   │
                          │  (static assets)   │
                          └─────────┬──────────┘
                                    │
                          ┌─────────▼──────────┐
                          │  Reverse Proxy     │
                          │  (Nginx / Caddy)   │
                          │  + TLS termination │
                          └─────────┬──────────┘
                                    │
            ┌───────────────────────┼───────────────────────┐
            │                       │                       │
   ┌────────▼─────────┐   ┌─────────▼────────┐   ┌─────────▼────────┐
   │  App Server #1   │   │  App Server #N   │   │  Queue Worker    │
   │  PHP-FPM 8.3     │   │  PHP-FPM 8.3     │   │  php artisan      │
   │  Laravel 13      │   │  Laravel 13      │   │  queue:work       │
   └────────┬─────────┘   └────────┬────────┘   └─────────┬────────┘
            │                       │                       │
            └───────────────┬───────┴───────────────────────┘
                            │
                  ┌─────────▼──────────┐
                  │   MongoDB Cluster  │
                  │   (Replica Set)    │
                  │   db_anarcyxreptile│
                  └────────────────────┘
```

**Checklist deploy:**

- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] `npm run build`
- [ ] `php artisan config:cache route:cache view:cache`
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false`
- [ ] `php artisan key:generate` (sekali per server)
- [ ] Setup cron: `* * * * * php artisan schedule:run`
- [ ] Setup supervisor untuk `queue:work`
- [ ] Backup MongoDB berkala (mongodump / Atlas snapshots)

---

## 11. Testing & Quality

- **PHPUnit 12** sudah terpasang (`phpunit.xml`).
- Jalankan: `composer test` → `php artisan test`
- Test pattern mengikuti PSR-4: `Tests\\` → `tests/`
- **Laravel Pint** untuk code style: `vendor/bin/pint`
- **Laravel Pail** untuk tail log saat dev.

**Lokasi test yang disarankan:**

```
tests/
├── Feature/
│   ├── HomeTest.php            ← storefront dapat diakses publik
│   ├── ProductDetailTest.php   ← detail produk + 404 handling
│   ├── AdminAuthTest.php       ← login/logout & middleware
│   └── AdminProductTest.php    ← CRUD produk (butuh auth)
└── Unit/
    └── Models/
        ├── ReptileTest.php     ← casting, fillable
        └── OrderTest.php       ← relasi embedded items
```

---

## 12. Roadmap Arsitektur (Saran Pengembangan)

| Prioritas | Item | Alasan |
|---|---|---|
| Tinggi | Pisahkan **CartController** & **OrderController** + **PaymentController** | `cart` masih closure; belum ada alur checkout |
| Tinggi | Tambahkan **customer auth flow** (register/login) | Sekarang hanya admin yang bisa login |
| Sedang | Tambahkan **Service Layer** (mis. `OrderService`, `ReptileService`) | Supaya controller tidak gemuk saat logic bertambah |
| Sedang | Pisahkan **koneksi MongoDB** vs **SQL fallback** (untuk cache/session via Redis) | Performa & skalabilitas |
| Sedang | Tambah **index** MongoDB: `users.email`, `reptiles.category`, `orders.status`, `orders.created_at` | Query cepat saat koleksi membesar |
| Rendah | Migrasikan gambar produk ke **S3 / DigitalOcean Spaces** | Skalabilitas storage |
| Rendah | Tambah **REST API** (route/api.php + Sanctum) untuk kebutuhan mobile app | Omnichannel |
| Rendah | Tambah **observability** (Sentry / Telescope) | Monitoring error & query |

---

## 13. Diagram Ringkas

### 13.1 Request Lifecycle
```
[Client]──HTTP──▶[public/index.php]──▶[bootstrap/app.php]
                                              │
                              ┌───────────────┼───────────────┐
                              ▼               ▼               ▼
                          [Routes]      [Middleware]    [Service Providers]
                              │           (auth, csrf)         │
                              ▼                               │
                       [Controller]◀───────────────────────────┘
                              │
                  ┌───────────┼───────────┐
                  ▼                       ▼
              [Model]──▶[MongoDB]    [View (Blade)]
                                          │
                                          ▼
                              [HTML + CSS + JS response]
                                          │
                                          ▼
                                       [Client]
```

### 13.2 Arsitektur 3-Layer
```
┌─────────────────────────────────────┐
│  PRESENTATION  (Blade + Tailwind)   │
└──────────────────┬──────────────────┘
                   │
┌──────────────────▼──────────────────┐
│  APPLICATION    (Controllers)       │
└──────────────────┬──────────────────┘
                   │
┌──────────────────▼──────────────────┐
│  DATA            (Models + MongoDB) │
└─────────────────────────────────────┘
```

---

## 14. Kesimpulan

ANARCYX saat ini adalah **MVP e-commerce reptil** yang solid dengan fondasi:

* ✅ Laravel 13 + MongoDB (NoSQL) — fleksibel & scalable
* ✅ MVC pattern yang bersih
* ✅ Admin dashboard dengan CRUD & agregasi real-time
* ✅ Sistem notifikasi sederhana
* ✅ Keamanan standar Laravel (auth, CSRF, hashing, validasi)

Dengan mengikuti roadmap di bagian 12, sistem ini siap berevolusi menjadi
platform e-commerce reptile production-grade yang lengkap dengan checkout,
payment gateway, dan omnichannel.
