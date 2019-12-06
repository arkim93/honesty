<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'image',
        'currency',
        'currency_placement',
        'balance',
    ];
}
