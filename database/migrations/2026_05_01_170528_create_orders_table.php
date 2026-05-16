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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'confirmed', 'ready', 'collected'])->default('pending');
            $table->enum('fulfillment', ['pickup', 'delivery'])->default('pickup');
            $table->decimal('total_amount', 10, 2);
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->text('delivery_address')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};