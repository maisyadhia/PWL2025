<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->id('stok_id'); // ID unik, bukan 'id' default
            $table->string('barang_kode')->unique(); // Contoh: B6G002
            $table->unsignedBigInteger('barang_id');
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->date('tanggal_update');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_barang');
    }
};
