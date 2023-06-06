<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('groups')->delete();
        
        \DB::table('groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_project' => '1',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 1',
                'descripcion' => 'Descripcion del grupo 1',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'id_project' => '2',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 2',
                'descripcion' => 'Descripcion del grupo 2',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'id_project' => '3',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 3',
                'descripcion' => 'Descripcion del grupo 3',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'id_project' => '4',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 4',
                'descripcion' => 'Descripcion del grupo 4',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'id_project' => '5',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 5',
                'descripcion' => 'Descripcion del grupo 5',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'id_project' => '6',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 6',
                'descripcion' => 'Descripcion del grupo 6',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'id_project' => '7',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 7',
                'descripcion' => 'Descripcion del grupo 7',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'id_project' => '8',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 8',
                'descripcion' => 'Descripcion del grupo 8',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'id_project' => '9',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 9',
                'descripcion' => 'Descripcion del grupo 9',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'id_project' => '10',
                'id_user' => '1',
                'id_supervisor' => NULL,
                'grupo' => 'Grupo 10',
                'descripcion' => 'Descripcion del grupo 10',
                'estatus' => 1,
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}