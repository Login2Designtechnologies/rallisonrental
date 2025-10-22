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
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('property_id')->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->unsignedBigInteger('utility_id')->nullable();
            $table->foreign('utility_id')->references('id')->on('utilities_catg')->onDelete('cascade');

            $table->string('invoice_month', 10)->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();

            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            
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
        Schema::dropIfExists('utility_bills');
    }
};
