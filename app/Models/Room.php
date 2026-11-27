<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'facility_id',
        'room_number',
        'room_name',
        'capacity',
        'floor',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(RoomUsage::class);
    }

    public function collegeAssignments(): HasMany
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
