<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InsurancesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('insurances')->delete();
        
        \DB::table('insurances')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre_aseguradora' => 'AXA',
                'status' => 1,
                'created_at' => '2023-05-17 13:00:57',
                'updated_at' => '2023-05-17 13:00:57',
            ),
            1 => 
            array (
                'id' => 2,
                'nombre_aseguradora' => 'QUALITAS',
                'status' => 1,
                'created_at' => '2023-05-17 13:01:05',
                'updated_at' => '2023-05-17 13:01:05',
            ),
            2 => 
            array (
                'id' => 3,
                'nombre_aseguradora' => 'MAPFRE',
                'status' => 1,
                'created_at' => '2023-05-17 13:01:12',
                'updated_at' => '2023-05-17 13:01:12',
            ),
        ));
        
        
    }
}