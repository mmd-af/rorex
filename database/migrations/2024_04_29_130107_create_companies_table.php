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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('activity_domain');
            $table->string('vat_id');
            $table->string('registration_number');
            $table->string('country');
            $table->string('county');
            $table->string('city');
            $table->string('zip_code')->nullable();
            $table->text('address');
            $table->string('building')->nullable();
            $table->string('person_name');
            $table->string('job_title')->nullable();
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
