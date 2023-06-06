<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrador',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Supervisor',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Agente de Cobranza',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Agente de Ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Agente Renovaciones',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Coordinador',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Director',
                'guard_name' => 'web',
                'created_at' => '2023-05-26 13:09:44',
                'updated_at' => '2023-05-26 13:09:44',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'BI',
                'guard_name' => 'web',
                'created_at' => '2023-06-01 10:22:45',
                'updated_at' => '2023-06-01 10:22:45',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Marketing Dashboard',
                'guard_name' => 'web',
                'created_at' => '2023-06-02 16:24:14',
                'updated_at' => '2023-06-02 16:24:14',
            ),
        ));
        
        
    }
}