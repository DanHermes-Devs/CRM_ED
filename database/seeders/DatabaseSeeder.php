<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\SeederUser;
use Illuminate\Support\Facades\DB;
use Database\Seeders\SeederTablaGroups;
use Database\Seeders\SeederTablaPermisos;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ModelHasPermissionsTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(PaisesTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        // $this->call(GroupsTableSeeder::class);
        $this->call(VentasTableSeeder::class);
        $this->call(ReceiptsTableSeeder::class);
        $this->call(InsurancesTableSeeder::class);
        $this->call(CampaignsTableSeeder::class);
        $this->call(AttendancesTableSeeder::class);
        $this->call(IncidentsTableSeeder::class);
        $this->call(EducationTableSeeder::class);
    }
}