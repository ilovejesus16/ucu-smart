<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'instructor_id',
        'subject_code',
        'section_name',
        'subject_name',
        'units',
        'enrolled',
        'day',
        'start_time',
        'end_time',
        'semester',
        'school_year',
        'status',
    ];

    protected $casts = [
        'units' => 'decimal:2',
        'enrolled' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

   public function usages()
{
    return $this->hasMany(RoomUsage::class);
}

    public function occurrences()
{
    return $this->hasMany(ClassOccurrence::class);
}
} 