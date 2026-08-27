<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomUsage;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'room_number',
        'room_name',
        'capacity',
        'floor',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    
    public function schedules()
{
    return $this->hasMany(Schedule::class);
}

public function usages()
{
    return $this->hasMany(RoomUsage::class);
}

}
