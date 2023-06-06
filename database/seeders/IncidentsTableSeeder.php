<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IncidentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('incidents')->delete();
        
        
        
    }
}