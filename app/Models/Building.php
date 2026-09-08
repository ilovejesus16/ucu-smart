<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'building_code',
        'building_name',
        'image',
        'map_x',
        'map_y',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}