<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterItem extends Model
{
    // define Master Item model
    protected $fillable = [
        'name',
        'code',
        'attachment',
        'status',
        'category_id',
        'brand_id',
        'user_id',
    ];
}
