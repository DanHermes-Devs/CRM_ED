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
        $this->call(SeederTablaPermisos::class);
        $this->call(SeederUser::class);
        $this->call(SeederTablaPaises::class);
        $this->call(SeederTablaProjects::class);
        $this->call(SeederTablaGroups::class);
        // $this->call(VentasTableSeeder::class);
        $this->call(EducationTableSeeder::class);
    }
}