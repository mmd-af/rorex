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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('staff_code')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('cart_number')->unique()->nullable();
            $table->string('password')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('identity_card')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('nationality')->nullable();
            $table->string('education')->nullable();
            $table->string('profession')->nullable();
            $table->date('departure_date')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('address')->nullable();
            $table->float('leave_balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
