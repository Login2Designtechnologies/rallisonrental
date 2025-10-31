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
        Schema::create('utility_shares', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('utility_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();

            $table->char('invoice_month', 10)->nullable();

            $table->decimal('price', 10, 2)->default(0)->nullable();
            $table->decimal('percentage', 5, 2)->default(0)->nullable();
            $table->decimal('amount', 10, 2)->default(0)->nullable();

            $table->timestamps();

            $table->foreign('property_id')
                  ->references('id')
                  ->on('properties')
                  ->onDelete('cascade');

            $table->foreign('utility_id')
                  ->references('id')
                  ->on('property_utilities_main')
                  ->onDelete('cascade');

            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_shares');
    }
};
