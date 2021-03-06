<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('point_value');
            $table->integer('pair_value');
            $table->integer('pair_amount');
            $table->integer('tds');
            $table->integer('service_charge');
            $table->integer('sponsor_Commisions');
            $table->integer('joinfee');

            $table->integer('fast_track');
            $table->integer('indirectfast_track_one');
            $table->integer('indirectfast_track_two');
            $table->integer('indirectfast_track_three');
            $table->integer('binary_bonus');
            $table->integer('matching_bonus_one');
            $table->integer('matching_bonus_two');
            $table->integer('matching_bonus_three');
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
       Schema::drop('settings');
    }
}
