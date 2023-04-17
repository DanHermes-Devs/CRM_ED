<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'receipt_id',
        'user_id',
        'action',
        'notes'
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
