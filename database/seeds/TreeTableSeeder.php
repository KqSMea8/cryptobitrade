<?php

use Illuminate\Database\Seeder;

class TreeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
       \App\Tree_Table::create([
            'sponsor'        => '0',
            'user_id'        => '1',
            'placement_id'   => 0,
            'leg'   => 'L',
            'type'   => 'yes'
        ]); 
       \App\Tree_Table::create([
            'sponsor'        => 1,            
            'user_id'        => '0',
            'placement_id'   => 1,
            'leg'   => 'L',
            'type'   => 'vaccant'
        ]); 
         \App\Tree_Table::create([
			'sponsor' 	     => 1,
            'user_id'        => '0',
			'placement_id'   => 1,
            'leg'   => 'R',
			'type'   => 'vaccant'
		]);	
    }
}
