<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusLocation extends Model
{
    protected $fillable = [
        'number',
        'name',
        'type',
        'building_id',
        'map_x',
        'map_y',
        'marker_size',
        'description',
        'total_floor_area',
'year_constructed',
'classroom_count',
'laboratory_count',
'office_count',
'restroom_count',
'function_room_count',
    ];

    protected $casts = [
        'number' => 'integer',
        'building_id' => 'integer',
        'map_x' => 'float',
        'map_y' => 'float',
        'marker_size' => 'integer',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
