<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilities_sub', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('utility_main_id')->nullable();
            $table->foreign('utility_main_id')->references('id')->on('properties')->onDelete('cascade');

            $table->string('sub_category_name')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilities_sub');
    }
};
