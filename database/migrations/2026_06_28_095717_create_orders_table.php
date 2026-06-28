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

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_address_id')
                ->nullable()
                ->constrained('user_addresses')
                ->nullOnDelete();

            $table->string('order_number')->unique();

            $table->enum('delivery_method', ['home_trial', 'standard'])->default('standard');
            $table->enum('payment_method', ['cod', 'online'])->default('cod');

            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

            $table->enum('order_status', [
                'placed',
                'confirmed',
                'packed',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('placed');

            $table->decimal('total_mrp', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('home_trial_fee', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total_payable', 10, 2)->default(0);

            // Address snapshot
            $table->string('shipping_full_name')->nullable();
            $table->string('shipping_mobile', 20)->nullable();
            $table->string('shipping_pincode', 10)->nullable();
            $table->string('shipping_flat_house')->nullable();
            $table->string('shipping_area_street')->nullable();
            $table->string('shipping_landmark')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_address_type')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};