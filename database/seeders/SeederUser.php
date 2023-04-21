<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SeederUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'usuario' => 'tec01dr1',
            'name' => 'Dan Hermes',
            'apellido_paterno' => 'Reyes',
            'apellido_materno' => 'Osnaya',
            'email' => 'dreyes@exponentedigital.mx',
            'password' => bcrypt('danhermes123456'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Administrador');
        
        User::create([
            'usuario' => 'adminexponente',
            'name' => 'Daniel',
            'apellido_paterno' => 'Lopez',
            'apellido_materno' => 'Lopes',
            'email' => 'tecnologia@exponentedigital.mx',
            'password' => bcrypt('daniel123456'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Administrador');
        
        User::create([
            'usuario' => 'Agente',
            'name' => 'Agente',
            'apellido_paterno' => '1',
            'apellido_materno' => '2',
            'email' => 'agente@exponentedigital.mx',
            'password' => bcrypt('agente123456'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Agente de Ventas');
        
        User::create([
            'usuario' => 'Agente2',
            'name' => 'Agente 2',
            'apellido_paterno' => '3',
            'apellido_materno' => '4',
            'email' => 'agente2@exponentedigital.mx',
            'password' => bcrypt('agente123456'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Agente de Ventas');
        
        User::create([
            'usuario' => 'AgenteCobranza',
            'name' => 'Agente Cobranza',
            'apellido_paterno' => '3',
            'apellido_materno' => '4',
            'email' => 'agentecobranza@exponentedigital.mx',
            'password' => bcrypt('agente123456'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Agente de Cobranza');
        
        User::create([
            'usuario' => 'cor01sc1',
            'name' => 'Sergio',
            'apellido_paterno' => 'Camaño',
            'apellido_materno' => 'Sanchez',
            'email' => 'sergio@exponentedigital.mx',
            'password' => bcrypt('cor01sc1'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Supervisor');
        
        User::create([
            'usuario' => 'sup01',
            'name' => 'Supervisor 1',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'email' => 'supervisor@exponentedigital.mx',
            'password' => bcrypt('sup01'),
            'estatus' => 1,
            'fecha_ultimo_login' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Supervisor');
    }
}
