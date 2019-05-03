<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('addproducts', function ($table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('productcode');
    $table->string('productname');
    $table->string('description');
    $table->string('category'); 
    $table->integer('amount');
    $table->integer('pv');
    $table->integer('quantity');
    $table->string('image');
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
       Schema::dropIfExists('addproducts');   
    }
}
