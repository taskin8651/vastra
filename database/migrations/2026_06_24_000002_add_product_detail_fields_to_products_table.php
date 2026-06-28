<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('colour')->nullable()->after('image_path');
            $table->json('available_colours')->nullable()->after('colour');
            $table->json('available_sizes')->nullable()->after('available_colours');
            $table->string('closure_type')->nullable()->after('available_sizes');
            $table->string('fashion_type')->nullable()->after('closure_type');
            $table->string('hemline')->nullable()->after('fashion_type');
            $table->string('knit_or_woven')->nullable()->after('hemline');
            $table->string('product_length')->nullable()->after('knit_or_woven');
            $table->string('season')->nullable()->after('product_length');
            $table->string('transparency')->nullable()->after('season');
            $table->string('stretchability')->nullable()->after('transparency');
            $table->string('wash_care')->nullable()->after('stretchability');
            $table->string('fit_type')->nullable()->after('wash_care');
            $table->string('fabric_details')->nullable()->after('fit_type');
            $table->string('fabric_composition')->nullable()->after('fabric_details');
            $table->string('occasion')->nullable()->after('fabric_composition');
            $table->string('pattern_type')->nullable()->after('occasion');
            $table->string('sleeve_length')->nullable()->after('pattern_type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['colour', 'available_colours', 'available_sizes', 'closure_type', 'fashion_type', 'hemline', 'knit_or_woven', 'product_length', 'season', 'transparency', 'stretchability', 'wash_care', 'fit_type', 'fabric_details', 'fabric_composition', 'occasion', 'pattern_type', 'sleeve_length']);
        });
    }
};
