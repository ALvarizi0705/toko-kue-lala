<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode unik bahan
            $table->string('name');
            $table->string('unit'); // kg, gram, liter, dll
            $table->decimal('stock', 12, 3)->default(0); // stok saat ini
            $table->decimal('min_stock', 12, 3)->default(0); // batas minimum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
