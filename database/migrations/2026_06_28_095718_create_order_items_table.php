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

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('product_name');
            $table->string('brand_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('sku')->nullable();

            $table->string('size')->nullable();
            $table->string('colour')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('mrp', 10, 2)->default(0);
            $table->integer('qty')->default(1);

            $table->decimal('line_total', 10, 2)->default(0);
            $table->decimal('line_mrp', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};