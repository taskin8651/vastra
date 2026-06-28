<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('return_number')->unique();

            $table->enum('reason', [
                'size_issue',
                'damaged_product',
                'wrong_item',
                'quality_issue',
                'other',
            ]);

            $table->text('description')->nullable();

            $table->enum('refund_method', [
                'cash',
                'upi',
                'bank',
                'wallet',
            ])->default('cash');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'picked_up',
                'refunded',
            ])->default('pending');

            $table->decimal('requested_amount', 10, 2)->default(0);

            $table->text('admin_note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};