<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_request_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('return_request_id')
                ->constrained('return_requests')
                ->cascadeOnDelete();

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            $table->string('product_name');
            $table->string('brand_name')->nullable();
            $table->string('size')->nullable();
            $table->string('colour')->nullable();

            $table->integer('qty')->default(1);
            $table->decimal('amount', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request_items');
    }
};