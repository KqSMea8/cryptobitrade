<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalCusomterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_customers', function (Blueprint $table) {
             $table->increments('id');
            $table->text('data');
            $table->text('paypal_data')->nullable();
            $table->text('paypal_payment_responce')->nullable();
            $table->text('paypal_recurring_responce')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal_customers');
    }
}
