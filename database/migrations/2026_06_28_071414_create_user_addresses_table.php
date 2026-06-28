<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('mobile', 20);

            $table->string('pincode', 10);
            $table->string('flat_house');
            $table->string('area_street');
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state');

            $table->enum('address_type', ['home', 'work', 'other'])->default('home');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('save_for_future')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};