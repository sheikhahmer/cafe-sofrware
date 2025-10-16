<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('contact')->nullable();
            $table->text('address')->nullable();
            $table->enum('order_type', ['dine_in', 'takeaway', 'delivery']);
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('waiter_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('service_charges', 10, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('manual_discount', 10, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->nullable();
            $table->decimal('delivery_charges', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('rider_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
