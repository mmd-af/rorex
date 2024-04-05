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
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->foreignId('edit_by')->nullable()->after('remarca');
            $table->foreign('edit_by')->references('id')->on('users')->onDelete('cascade');
            $table->float('plus_holiday_night')->default(0)->after('ot_ore');
            $table->float('plus_holiday_day')->default(0)->after('ot_ore');
            $table->float('plus_week_night')->default(0)->after('ot_ore');
            $table->float('plus_week_day')->default(0)->after('ot_ore');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropForeign(['edit_by']);
            $table->dropColumn('edit_by');
            $table->dropColumn('plus_holiday_night');
            $table->dropColumn('plus_holiday_day');
            $table->dropColumn('plus_week_night');
            $table->dropColumn('plus_week_day');
        });
    }
};
