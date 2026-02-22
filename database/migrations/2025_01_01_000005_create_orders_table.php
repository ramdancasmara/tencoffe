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
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('order_type'); // dine-in, pickup, delivery
            $table->integer('subtotal');
            $table->integer('delivery_fee')->default(0);
            $table->integer('total');
            $table->string('payment_method'); // manual, whatsapp, duitku
            $table->string('payment_status')->default('pending');
            $table->string('duitku_reference')->nullable();
            $table->string('duitku_payment_url')->nullable();
            $table->string('duitku_va_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
