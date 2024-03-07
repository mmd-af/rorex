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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cod_staff')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('departament')->nullable();
            $table->string('pozitie')->nullable();
            $table->string('numar_card')->unique()->nullable();
            $table->string('parola')->nullable();
            $table->date('data_aderarii')->nullable();
            $table->string('sex')->nullable();
            $table->string('starea_civila')->nullable();
            $table->date('data_nasterii')->nullable();
            $table->string('telefon')->nullable();
            $table->string('card_de_identitate')->nullable();
            $table->string('functie')->nullable();
            $table->string('tip_personal')->nullable();
            $table->string('cod_postal')->nullable();
            $table->string('status_politic')->nullable();
            $table->string('rezidenta')->nullable();
            $table->string('nationalitate')->nullable();
            $table->string('educatie')->nullable();
            $table->date('data_absolvirii')->nullable();
            $table->string('scoala')->nullable();
            $table->string('profesie')->nullable();
            $table->date('data_plecarii')->nullable();
            $table->string('prenumele_tatalui')->nullable();
            $table->string('adresa')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('rolles')->nullable();
            $table->float('leave_balance')->default(0);
            $table->boolean('is_active')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
