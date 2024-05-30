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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('product_number')->nullable();
            $table->date('from_date');
            $table->date('until_date');
            $table->string('country_of_origin');
            $table->string('city_of_origin');
            $table->string('destination_country');
            $table->string('destination_city');
            $table->string('truck_type')->nullable();
            $table->string('weight_of_each_car');
            $table->text('description');
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};
