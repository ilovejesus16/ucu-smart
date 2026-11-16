<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassOccurrence extends Model
{
    protected $fillable = [
        'schedule_id',
        'class_date',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'class_date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
