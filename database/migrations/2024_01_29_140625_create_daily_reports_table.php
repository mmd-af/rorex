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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cod_staff');
            $table->foreign('cod_staff')->references('id')->on('users')->onDelete('cascade');
            $table->string('nume');
            $table->date('data');
            $table->string('saptamana');
            $table->string('nume_schimb');
            $table->string('on_work1')->nullable();
            $table->string('off_work1')->nullable();
            $table->string('on_work2')->nullable();
            $table->string('off_work2')->nullable();
            $table->string('on_work3')->nullable();
            $table->string('off_work3')->nullable();
            $table->string('absenta_zile')->nullable();
            $table->string('munca_ore')->nullable();
            $table->string('ot_ore')->nullable();
            $table->string('tarziu_minute')->nullable();
            $table->string('devreme_minute')->nullable();
            $table->string('lipsa_ceas_timpi')->nullable();
            $table->string('sarbatoare_publica_ore')->nullable();
            $table->string('concediu_ore')->nullable();
            $table->string('remarca')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
