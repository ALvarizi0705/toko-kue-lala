<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('restrict');
            $table->decimal('difference', 12, 3); // bisa positif/negatif
            $table->text('reason');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
