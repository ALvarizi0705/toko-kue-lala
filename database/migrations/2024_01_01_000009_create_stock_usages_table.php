<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('restrict');
            $table->decimal('qty', 12, 3);
            $table->date('date');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->timestamps();
            $table->index('ingredient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_usages');
    }
};
