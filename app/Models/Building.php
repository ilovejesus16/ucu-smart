<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'building_code',
        'building_name',
        'description',
        'image',
        'map_x',
        'map_y',
         'total_floor_area',
    'year_constructed',
    'classroom_count',
    'laboratory_count',
    'office_count',
    'restroom_count',
    'function_room_count',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function campusLocation()
    {
        return $this->hasOne(CampusLocation::class);
    }
}
