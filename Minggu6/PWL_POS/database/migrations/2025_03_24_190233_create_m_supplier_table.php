<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
    Schema::create('m_supplier', function (Blueprint $table) {
        $table->id('supplier_id'); // Primary Key dengan auto increment
        $table->string('nama_supplier', 255);
        $table->text('alamat');
        $table->string('supplier_kontak', 50);
        $table->timestamps(); // created_at dan updated_at otomatis
    });
    }

    public function down()
    {
        Schema::dropIfExists('m_supplier');
    }

};
