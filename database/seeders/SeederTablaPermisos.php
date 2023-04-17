<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Eliminamos los permisos y roles que ya existan para poder ejecutar el seeder
        \DB::table('permissions')->delete();
        \DB::table('roles')->delete();

        $rol1 = Role::create(['name' => 'Administrador']);
        $rol2 = Role::create(['name' => 'Supervisor']);
        $rol3 = Role::create(['name' => 'Agente de Cobranza']);
        $rol4 = Role::create(['name' => 'Agente de Ventas']);
        $rol5 = Role::create(['name' => 'Agente Renovaciones']);
        $rol6 = Role::create(['name' => 'Coordinador']);

        $permisos = [
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'borrar-usuarios',
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',
            'ver-paises',
            'crear-paises',
            'editar-paises',
            'borrar-paises',
            'ver-ventas',
            'crear-ventas',
            'editar-ventas',
            'borrar-ventas',
            'ver-campos',
            'ver-proyectos',
            'crear-proyectos',
            'editar-proyectos',
            'borrar-proyectos',
            'ver-grupos',
            'crear-grupos',
            'editar-grupos',
            'borrar-grupos',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }

        $rol1->givePermissionTo($permisos);
        $rol2->givePermissionTo('ver-usuarios');
        $rol4->givePermissionTo('ver-ventas');
    }
}
