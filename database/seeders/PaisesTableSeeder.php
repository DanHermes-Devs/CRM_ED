<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('paises')->delete();
        
        \DB::table('paises')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pais' => 'México',
                'descripcion' => 'País de América',
                'estatus' => '1',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            1 => 
            array (
                'id' => 2,
                'pais' => 'Estados Unidos',
                'descripcion' => 'País de América',
                'estatus' => '1',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            2 => 
            array (
                'id' => 3,
                'pais' => 'Canadá',
                'descripcion' => 'País de América',
                'estatus' => '1',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            3 => 
            array (
                'id' => 4,
                'pais' => 'Brasil',
                'descripcion' => 'País de América',
                'estatus' => '1',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            4 => 
            array (
                'id' => 5,
                'pais' => 'Argentina',
                'descripcion' => 'País de América',
                'estatus' => '1',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            5 => 
            array (
                'id' => 6,
                'pais' => 'Colombia',
                'descripcion' => 'País de América',
                'estatus' => '0',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            6 => 
            array (
                'id' => 7,
                'pais' => 'Perú',
                'descripcion' => 'País de América',
                'estatus' => '0',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            7 => 
            array (
                'id' => 8,
                'pais' => 'Chile',
                'descripcion' => 'País de América',
                'estatus' => '0',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            8 => 
            array (
                'id' => 9,
                'pais' => 'Venezuela',
                'descripcion' => 'País de América',
                'estatus' => '0',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
            9 => 
            array (
                'id' => 10,
                'pais' => 'Ecuador',
                'descripcion' => 'País de América',
                'estatus' => '0',
                'deleted_at' => NULL,
                'created_at' => '2023-04-26 23:21:16',
                'updated_at' => '2023-04-26 23:21:16',
            ),
        ));
        
        
    }
}