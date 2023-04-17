<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeederTablaProjects extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mostrar 10 projectos reales
        $proyectos = [
            [
                'id_pais' => 1,
                'proyecto' => 'Proyecto 1',
                'descripcion' => 'Descripción del proyecto 1',
                'estatus' => 1
            ],
            [
                'id_pais' => 2,
                'proyecto' => 'Proyecto 2',
                'descripcion' => 'Descripción del proyecto 2',
                'estatus' => 1
            ],
            [
                'id_pais' => 3,
                'proyecto' => 'Proyecto 3',
                'descripcion' => 'Descripción del proyecto 3',
                'estatus' => 1
            ],
            [
                'id_pais' => 4,
                'proyecto' => 'Proyecto 4',
                'descripcion' => 'Descripción del proyecto 4',
                'estatus' => 1
            ],
            [
                'id_pais' => 5,
                'proyecto' => 'Proyecto 5',
                'descripcion' => 'Descripción del proyecto 5',
                'estatus' => 1
            ],
            [
                'id_pais' => 6,
                'proyecto' => 'Proyecto 6',
                'descripcion' => 'Descripción del proyecto 6',
                'estatus' => 1
            ],
            [
                'id_pais' => 7,
                'proyecto' => 'Proyecto 7',
                'descripcion' => 'Descripción del proyecto 7',
                'estatus' => 1
            ],
            [
                'id_pais' => 8,
                'proyecto' => 'Proyecto 8',
                'descripcion' => 'Descripción del proyecto 8',
                'estatus' => 1
            ],
            [
                'id_pais' => 9,
                'proyecto' => 'Proyecto 9',
                'descripcion' => 'Descripción del proyecto 9',
                'estatus' => 1
            ],
            [
                'id_pais' => 10,
                'proyecto' => 'Proyecto 10',
                'descripcion' => 'Descripción del proyecto 10',
                'estatus' => 1
            ]
        ];

        foreach ($proyectos as $proyecto) {
            DB::table('projects')->insert($proyecto);
        }
    }
}
