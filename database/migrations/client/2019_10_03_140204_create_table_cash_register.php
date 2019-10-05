<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCashRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('client')->create('cash_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('opening_data');
            $table->dateTime('closing_data');
            $table->float('opening_value', 8, 2);
            $table->float('total_sale', 8, 2);
            $table->float('total_closure', 8, 2);
            $table->integer('user');
            $table->boolean('status');
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
        Schema::connection('client')->dropIfExists('cash_registers');
    }
}
