<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'ver-usuarios',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'crear-usuarios',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'editar-usuarios',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'borrar-usuarios',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'ver-rol',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'crear-rol',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'editar-rol',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'borrar-rol',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'ver-paises',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'crear-paises',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'editar-paises',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'borrar-paises',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'ver-ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'crear-ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'editar-ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'borrar-ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'ver-campos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'ver-proyectos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'crear-proyectos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'editar-proyectos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'borrar-proyectos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'ver-grupos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'crear-grupos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'editar-grupos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'borrar-grupos',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'exportar-ventas',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            26 => 
            array (
                'id' => 29,
                'name' => 'ver-cobranza',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            27 => 
            array (
                'id' => 30,
                'name' => 'crear-cobranza',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            28 => 
            array (
                'id' => 31,
                'name' => 'editar-cobranza',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            29 => 
            array (
                'id' => 32,
                'name' => 'borrar-cobranza',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            30 => 
            array (
                'id' => 33,
                'name' => 'ver-aseguradoras',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            31 => 
            array (
                'id' => 34,
                'name' => 'crear-aseguradoras',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            32 => 
            array (
                'id' => 35,
                'name' => 'editar-aseguradoras',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            33 => 
            array (
                'id' => 36,
                'name' => 'borrar-aseguradoras',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            34 => 
            array (
                'id' => 37,
                'name' => 'ver-jobs',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            35 => 
            array (
                'id' => 38,
                'name' => 'crear-jobs',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            36 => 
            array (
                'id' => 39,
                'name' => 'editar-jobs',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            37 => 
            array (
                'id' => 40,
                'name' => 'borrar-jobs',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
            38 => 
            array (
                'id' => 41,
                'name' => 'ver-dashboard',
                'guard_name' => 'web',
                'created_at' => '2023-04-26 23:21:15',
                'updated_at' => '2023-04-26 23:21:15',
            ),
        ));
    }
}