<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reptile;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class MongoDBSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan data lama agar tidak duplikat saat di-run ulang
        User::truncate();
        Reptile::truncate();
        Order::truncate();

        // 2. Suntik Data Akun Admin Default (Role: admin)
        User::create([
            'name' => 'Sultan',
            'email' => 'admin', // Ini yang dipakai untuk input "Username" di form login
            'password' => Hash::make('admin123'), // Ini password-nya
            'role' => 'admin',
            'phone_number' => '62895613369443',
            'address' => 'Jakarta, Indonesia'
        ]);

        // 3. Suntik Data Sampel Produk Reptil ke Koleksi 'reptiles'
        $rep1 = Reptile::create([
            'name' => 'Rhinoceros Iguana',
            'category' => 'Iguana',
            'price' => 350000,
            'stock' => 5,
            'image' => '1716200001.jpg', // Dummy name untuk file gambar kamu nanti
            'desc' => 'Karakter jinak khas badak, memiliki tanduk unik kecil di bagian hidung depan.',
            'description' => 'Karakter jinak khas badak, memiliki tanduk unik kecil di bagian hidung depan. Reptil ini sangat cocok untuk kolektor pemula maupun berpengalaman, dengan perawatan yang relatif mudah dan temperamen yang tenang.',
            'short_description' => 'Karakter jinak khas badak, tanduk unik di hidung.',
            'attributes' => [
                'morph' => 'Cyclura cornuta',
                'weight' => '1.5kg',
                'age' => 'Juvenile'
            ]
        ]);

        $rep2 = Reptile::create([
            'name' => 'Leopard Gecko Hypo',
            'category' => 'Gecko',
            'price' => 650000,
            'stock' => 12,
            'image' => '1716200002.jpg',
            'desc' => 'Sangat cocok untuk pemula, warna kuning cerah bersih minim bintik hitam.',
            'description' => 'Sangat cocok untuk pemula, warna kuning cerah bersih minim bintik hitam. Leopard Gecko Hypo ini memiliki temperamen jinak dan mudah ditangani, menjadikannya pilihan sempurna untuk pertama kali memelihara reptil.',
            'short_description' => 'Sangat cocok untuk pemula, warna kuning cerah bersih.',
            'attributes' => [
                'morph' => 'Super Hypo Tangerine',
                'weight' => '45g',
                'age' => 'Adult'
            ]
        ]);

        $rep3 = Reptile::create([
            'name' => 'Ball Python Normal',
            'category' => 'Snake',
            'price' => 1200000,
            'stock' => 3,
            'image' => '1716200003.jpg',
            'desc' => 'Ular peliharaan paling tenang di dunia, bermotif eksotis alami.',
            'description' => 'Ular peliharaan paling tenang di dunia, bermotif eksotis alami. Ball Python dikenal dengan sifatnya yang lembut dan mudah dirawat, cocok untuk pemula maupun kolektor reptil berpengalaman.',
            'short_description' => 'Ular peliharaan paling tenang, motif eksotis alami.',
            'attributes' => [
                'morph' => 'Classic Wild Type',
                'weight' => '800g',
                'age' => 'Sub-Adult'
            ]
        ]);

        // 4. Suntik Data Transaksi Palsu ke Koleksi 'orders' untuk Mengisi Grafik
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

        $this->command->info('Database MongoDB AnarchyxReptile berhasil diisi data awal!');

        // Tambahkan di dalam fungsi run() MongoDBSeeder.php kamu:
        \App\Models\Notification::truncate();

        // Notifikasi Hari Ini (Recent)
        \App\Models\Notification::create([
            'type' => 'order',
            'message' => 'Pesanan baru masuk #ORD-98214 dari Rizky Ardiansyah',
            'is_read' => false,
            'created_at' => now()->subMinutes(10)->toDateTimeString()
        ]);

        \App\Models\Notification::create([
            'type' => 'user',
            'message' => 'Pengguna baru "Amalia Putri" telah mendaftar akun',
            'is_read' => false,
            'created_at' => now()->subHours(2)->toDateTimeString()
        ]);

        // Notifikasi Minggu Lalu (Last Week)
        \App\Models\Notification::create([
            'type' => 'order',
            'message' => 'Pembayaran Invoice #INV-8812 terverifikasi valid oleh sistem',
            'is_read' => true,
            'created_at' => now()->subDays(3)->toDateTimeString()
        ]);

        \App\Models\Notification::create([
            'type' => 'system',
            'message' => 'Stok unit reptile "Ball Python Normal" tersisa 3 ekor',
            'is_read' => true,
            'created_at' => now()->subDays(6)->toDateTimeString()
        ]);
    }
}