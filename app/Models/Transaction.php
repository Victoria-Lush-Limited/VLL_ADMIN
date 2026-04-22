<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_pk',
        'user_id',
        'username',
        'allocated',
        'consumed',
        'reference',
        'description',
    ];
}
