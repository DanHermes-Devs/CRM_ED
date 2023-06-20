<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSupervisor extends Model
{
    use HasFactory;

    protected $table = 'group_supervisors';

    protected $fillable = [
        'group_id',
        'user_id',
    ];

    // RELACION UNO A MUCHOS INVERSA
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // RELACION UNO A MUCHOS INVERSA
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
