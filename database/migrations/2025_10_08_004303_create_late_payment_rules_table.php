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
        Schema::create('late_payment_rules', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('tenant_contract_id');
            $table->foreign('tenant_contract_id')->references('id')->on('tenant_contracts')->onDelete('cascade');

            $table->integer('tier')->nullable();
            $table->integer('grace_days')->nullable();
            $table->time('time')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
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
        Schema::dropIfExists('late_payment_rules');
    }
};
