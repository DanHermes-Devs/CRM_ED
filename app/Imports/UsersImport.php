<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel
{
    private $rol;

    public function __construct($rol)
    {
        $this->rol = $rol;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = new User([
            'usuario'  => $row[0],
            'name'     => $row[1],
            'email'    => $row[2], 
            'apellido_paterno' => $row[3],
            'apellido_materno' => $row[4],
            'password' => bcrypt($row[5]),
            'estatus'  => $row[6],
        ]);

        $user->save();
        $user->assignRole($this->rol);

        return $user;
    }
}
