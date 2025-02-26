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
        // Membuat tabel items dengan kolom id, name, description, dan timestamps
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Kolom auto-increment primary key
            $table->string('name'); // Kolom untuk nama item
            $table->text('description'); // Kolom untuk deskripsi item
            $table->timestamps(); // Kolom created_at dan updated_at secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel items
        Schema::dropIfExists('items');
    }
};

