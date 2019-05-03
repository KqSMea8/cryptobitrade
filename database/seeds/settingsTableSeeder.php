<?php

use Illuminate\Database\Seeder;

class settingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \App\Settings::create([
            'point_value'        => '100',
            'pair_value'        => '10',
            'pair_amount'   => 100,
            'tds'   => '0',
            'service_charge'   => '0',
            'sponsor_Commisions'   => '100',
            'joinfee' => '70',

            'fast_track' => '10',
            'indirectfast_track_one' => '5',
            'indirectfast_track_two' => '3',
            'indirectfast_track_three' => '2',
            'binary_bonus' => '10',
            'matching_bonus_one' => '5',
            'matching_bonus_two' => '3',
            'matching_bonus_three' => '2',
        ]); 
    }
}
