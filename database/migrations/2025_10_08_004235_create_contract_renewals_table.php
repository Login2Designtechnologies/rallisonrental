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
        Schema::create('contract_renewals', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('tenant_contract_id');
            $table->foreign('tenant_contract_id')->references('id')->on('tenant_contracts')->onDelete('cascade');

            $table->decimal('amount_increase', 10, 2)->nullable();
            $table->string('start_month')->nullable();
            $table->string('end_month')->nullable();
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
        Schema::dropIfExists('contract_renewals');
    }
};
