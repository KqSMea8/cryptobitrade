<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductspurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('productspurchase', function ($table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('username');
    $table->string('address');
    $table->string('country');
    $table->string('state');
    $table->string('city');
    $table->string('zipcode');
    $table->string('image');
    $table->string('productname');
    $table->integer('quantity');
    $table->integer('pv');
    $table->double('amount');
    $table->boolean('approved_at')->default(false);
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->softDeletes();
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
       Schema::dropIfExists('productspurchase');
    }
}
