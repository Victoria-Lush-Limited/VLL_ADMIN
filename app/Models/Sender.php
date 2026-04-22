<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    protected $fillable = [
        'sender_id',
        'user_id',
        'id_status',
    ];
}
