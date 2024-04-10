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
        Schema::table('letter_assignments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('signed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_assignments', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
