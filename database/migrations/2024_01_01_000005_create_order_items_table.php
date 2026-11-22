<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name'); // snapshot nama produk
            $table->decimal('unit_price', 12, 2); // snapshot harga saat pesan
            $table->integer('qty');
            $table->string('size')->nullable(); // ukuran kue
            $table->string('cake_text')->nullable(); // tulisan di kue
            $table->text('custom_note')->nullable(); // catatan khusus
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
