<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direct_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->unique(); // DS-YYYYMMDD-XXXX
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // kasir
            $table->string('payment_method'); // tunai, transfer, qris
            $table->decimal('total', 12, 2);
            $table->timestamp('sold_at')->useCurrent();
            $table->timestamps();
            $table->index('sold_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direct_sales');
    }
};
