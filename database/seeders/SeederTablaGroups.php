<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederTablaGroups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creamos 10 grupos de prueba con datos de los usuarios y proyectos creados en los seeders
        for ($i = 1; $i <= 10; $i++) {
            DB::table('groups')->insert([
                'id_project' => $i,
                'id_user' => 1,
                'grupo' => 'Grupo ' . $i,
                'descripcion' => 'Descripcion del grupo ' . $i,
                'estatus' => 1,
            ]);
        }

    }
}
