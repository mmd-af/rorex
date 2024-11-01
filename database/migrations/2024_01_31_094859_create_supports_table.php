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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->string('organization')->nullable();
            $table->integer('cod_staff')->nullable();
            $table->foreignId('read_by')->nullable();
            $table->foreign('read_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
