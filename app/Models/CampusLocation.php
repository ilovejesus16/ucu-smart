<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusLocation extends Model
{
    protected $fillable = [
        'number',
        'name',
        'type',
        'map_x',
        'map_y',
    ];

    protected $casts = [
        'map_x' => 'float',
        'map_y' => 'float',
    ];
}