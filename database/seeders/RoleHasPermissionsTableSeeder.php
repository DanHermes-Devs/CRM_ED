<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_has_permissions')->delete();
        
        \DB::table('role_has_permissions')->insert(array (
            0 => 
            array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'permission_id' => 2,
                'role_id' => 1,
            ),
            2 => 
            array (
                'permission_id' => 3,
                'role_id' => 1,
            ),
            3 => 
            array (
                'permission_id' => 4,
                'role_id' => 1,
            ),
            4 => 
            array (
                'permission_id' => 5,
                'role_id' => 1,
            ),
            5 => 
            array (
                'permission_id' => 6,
                'role_id' => 1,
            ),
            6 => 
            array (
                'permission_id' => 7,
                'role_id' => 1,
            ),
            7 => 
            array (
                'permission_id' => 8,
                'role_id' => 1,
            ),
            8 => 
            array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            9 => 
            array (
                'permission_id' => 10,
                'role_id' => 1,
            ),
            10 => 
            array (
                'permission_id' => 11,
                'role_id' => 1,
            ),
            11 => 
            array (
                'permission_id' => 12,
                'role_id' => 1,
            ),
            12 => 
            array (
                'permission_id' => 13,
                'role_id' => 1,
            ),
            13 => 
            array (
                'permission_id' => 13,
                'role_id' => 2,
            ),
            14 => 
            array (
                'permission_id' => 13,
                'role_id' => 4,
            ),
            15 => 
            array (
                'permission_id' => 13,
                'role_id' => 5,
            ),
            16 => 
            array (
                'permission_id' => 13,
                'role_id' => 6,
            ),
            17 => 
            array (
                'permission_id' => 13,
                'role_id' => 8,
            ),
            18 => 
            array (
                'permission_id' => 14,
                'role_id' => 1,
            ),
            19 => 
            array (
                'permission_id' => 14,
                'role_id' => 2,
            ),
            20 => 
            array (
                'permission_id' => 15,
                'role_id' => 1,
            ),
            21 => 
            array (
                'permission_id' => 15,
                'role_id' => 2,
            ),
            22 => 
            array (
                'permission_id' => 16,
                'role_id' => 1,
            ),
            23 => 
            array (
                'permission_id' => 16,
                'role_id' => 2,
            ),
            24 => 
            array (
                'permission_id' => 17,
                'role_id' => 1,
            ),
            25 => 
            array (
                'permission_id' => 17,
                'role_id' => 7,
            ),
            26 => 
            array (
                'permission_id' => 17,
                'role_id' => 8,
            ),
            27 => 
            array (
                'permission_id' => 18,
                'role_id' => 1,
            ),
            28 => 
            array (
                'permission_id' => 19,
                'role_id' => 1,
            ),
            29 => 
            array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            30 => 
            array (
                'permission_id' => 21,
                'role_id' => 1,
            ),
            31 => 
            array (
                'permission_id' => 22,
                'role_id' => 1,
            ),
            32 => 
            array (
                'permission_id' => 23,
                'role_id' => 1,
            ),
            33 => 
            array (
                'permission_id' => 24,
                'role_id' => 1,
            ),
            34 => 
            array (
                'permission_id' => 25,
                'role_id' => 1,
            ),
            35 => 
            array (
                'permission_id' => 26,
                'role_id' => 1,
            ),
            36 => 
            array (
                'permission_id' => 26,
                'role_id' => 2,
            ),
            37 => 
            array (
                'permission_id' => 26,
                'role_id' => 6,
            ),
            38 => 
            array (
                'permission_id' => 26,
                'role_id' => 8,
            ),
            39 => 
            array (
                'permission_id' => 29,
                'role_id' => 1,
            ),
            40 => 
            array (
                'permission_id' => 29,
                'role_id' => 6,
            ),
            41 => 
            array (
                'permission_id' => 31,
                'role_id' => 1,
            ),
            42 => 
            array (
                'permission_id' => 31,
                'role_id' => 6,
            ),
            43 => 
            array (
                'permission_id' => 41,
                'role_id' => 9,
            ),
        ));
        
        
    }
}