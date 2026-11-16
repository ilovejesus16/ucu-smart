<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomUsage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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


public function collegeAssignments()
{
    return $this->hasMany(CollegeRoomAssignment::class);
}

public function colleges(): BelongsToMany
{
    return $this->belongsToMany(
        College::class,
        'college_room_assignments'
    )
        ->withPivot(['academic_year_id', 'room_type', 'remarks'])
        ->withTimestamps();
}
}
