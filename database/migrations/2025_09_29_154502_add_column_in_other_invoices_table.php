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
        Schema::table('other_invoices', function (Blueprint $table) {
            $table->string('invoice_no', 100)->unique()->after('id'); 
            $table->text('pay_url')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_invoices', function (Blueprint $table) {
            //
        });
    }
};
