<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class SeederTablaPaises extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seeder para mostrar 10 paises con sus datos reales
        $paises = [
            ['pais' => 'México', 'descripcion' => 'País de América', 'estatus' => '1'],
            ['pais' => 'Estados Unidos', 'descripcion' => 'País de América', 'estatus' => '1'],
            ['pais' => 'Canadá', 'descripcion' => 'País de América', 'estatus' => '1'],
            ['pais' => 'Brasil', 'descripcion' => 'País de América', 'estatus' => '1'],
            ['pais' => 'Argentina', 'descripcion' => 'País de América', 'estatus' => '1'],
            ['pais' => 'Colombia', 'descripcion' => 'País de América', 'estatus' => '0'],
            ['pais' => 'Perú', 'descripcion' => 'País de América', 'estatus' => '0'],
            ['pais' => 'Chile', 'descripcion' => 'País de América', 'estatus' => '0'],
            ['pais' => 'Venezuela', 'descripcion' => 'País de América', 'estatus' => '0'],
            ['pais' => 'Ecuador', 'descripcion' => 'País de América', 'estatus' => '0'],
        ];

        foreach ($paises as $pais) {
            Pais::create($pais);
        }

    }
}
