<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('reptiles', function (Blueprint $table) {
        $table->id(); // (1) Otomatis jadi BigInt Primary Key
        $table->string('name'); // (2) Nama reptil
        $table->enum('category', ['ular', 'kadal', 'kura-kura', 'lainnya']); // (3) Kategori
        $table->string('morph_genetics')->nullable(); // (4) Morph (dibikin nullable, jaga-jaga kalau kura-kura biasa ga ada morph-nya)
        $table->enum('sex', ['jantan', 'betina', 'unsex']); // (5) Jenis kelamin
        $table->string('age'); // (6) Umur
        $table->integer('weight'); // (7) Berat (gram)
        $table->integer('length'); // (8) Panjang (cm)
        $table->text('description')->nullable(); // (9) Keterangan tambahan (nullable biar aman)
        $table->text('care_instructions')->nullable(); // (10) Panduan perawatan
        $table->integer('price'); // (11) Harga
        $table->integer('stock'); // (12) Stok
        $table->string('image')->nullable(); // (13) Nama file foto (nullable buat antisipasi foto belum di-upload)
        $table->timestamps(); // (14) Ini otomatis bikin created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reptiles');
    }
};
