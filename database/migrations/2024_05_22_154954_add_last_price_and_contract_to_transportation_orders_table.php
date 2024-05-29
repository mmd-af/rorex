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
        Schema::table('transportation_orders', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('price');
            $table->string('contract')->nullable()->after('price');
            $table->string('last_price')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transportation_orders', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('contract');
            $table->dropColumn('last_price');
        });
    }
};
