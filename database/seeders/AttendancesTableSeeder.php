<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attendances')->delete();
        
        \DB::table('attendances')->insert(array (
            0 => 
            array (
                'id' => 1,
                'agent_id' => 4,
                'agente' => 'agente',
                'fecha_login' => '2023-05-29',
                'hora_login' => '11:53:29',
                'fecha_logout' => '2023-05-29',
                'hora_logout' => '19:30:18',
                'tipo_asistencia' => 'A',
                'skilldata' => NULL,
                'observaciones' => NULL,
                'created_at' => '2023-05-30 02:21:24',
                'updated_at' => '2023-05-30 02:21:24',
            ),
        ));
        
        
    }
}