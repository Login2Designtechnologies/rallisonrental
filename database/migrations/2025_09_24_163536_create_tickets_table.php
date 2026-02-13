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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 200)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 30)->nullable(); // e.g., open, in_progress, closed
            $table->string('category', 100)->nullable();
            $table->string('photo')->nullable();

            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            
            $table->foreignId('property_id')->nullable()->constrained('properties')->cascadeOnDelete();        
            
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
        Schema::dropIfExists('tickets');
    }
};
