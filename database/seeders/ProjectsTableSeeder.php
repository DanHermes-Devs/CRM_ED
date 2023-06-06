<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('projects')->delete();
        
        \DB::table('projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_pais' => 1,
                'proyecto' => 'Proyecto 1',
                'descripcion' => 'Descripción del proyecto 1',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'id_pais' => 2,
                'proyecto' => 'Proyecto 2',
                'descripcion' => 'Descripción del proyecto 2',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'id_pais' => 3,
                'proyecto' => 'Proyecto 3',
                'descripcion' => 'Descripción del proyecto 3',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'id_pais' => 4,
                'proyecto' => 'Proyecto 4',
                'descripcion' => 'Descripción del proyecto 4',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'id_pais' => 5,
                'proyecto' => 'Proyecto 5',
                'descripcion' => 'Descripción del proyecto 5',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'id_pais' => 6,
                'proyecto' => 'Proyecto 6',
                'descripcion' => 'Descripción del proyecto 6',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'id_pais' => 7,
                'proyecto' => 'Proyecto 7',
                'descripcion' => 'Descripción del proyecto 7',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'id_pais' => 8,
                'proyecto' => 'Proyecto 8',
                'descripcion' => 'Descripción del proyecto 8',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'id_pais' => 9,
                'proyecto' => 'Proyecto 9',
                'descripcion' => 'Descripción del proyecto 9',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'id_pais' => 10,
                'proyecto' => 'Proyecto 10',
                'descripcion' => 'Descripción del proyecto 10',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}