<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBrand extends Model
{
    // define Master Brand model
    protected $fillable = [
        'name',
        'code',
        'status',
    ];
}
