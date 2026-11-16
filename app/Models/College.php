<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class College extends Model
{
    protected $fillable = [
        'college_code',
        'college_name',
        'color',
    ];

    public function roomAssignments(): HasMany
    {
        return $this->hasMany(CollegeRoomAssignment::class);
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'college_room_assignments'
        )
            ->withPivot(['academic_year_id', 'room_type', 'remarks'])
            ->withTimestamps();
    }
}
