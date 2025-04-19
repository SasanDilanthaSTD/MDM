<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    // define Master Category model
    protected $fillable = [
        'name',
        'code',
        'status',
    ];
}
