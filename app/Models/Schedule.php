<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomUsage;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'instructor_id',
        'subject_code',
        'subject_name',
        'day',
        'start_time',
        'end_time',
        'semester',
        'school_year',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function usage()
    {
        return $this->hasOne(RoomUsage::class);
    }
}