<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // LALA-YYYYMMDD-XXXX
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->date('pickup_date');
            $table->text('note')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'diproses',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->string('payment_method')->default('whatsapp');
            $table->string('payment_proof')->nullable();
            $table->text('payment_reject_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('customer_phone');
            $table->index('status');
            $table->index('pickup_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
