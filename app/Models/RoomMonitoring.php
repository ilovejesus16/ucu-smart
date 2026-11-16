<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomMonitoring extends Model
{
    protected $fillable = [
        'schedule_id',
        'room_id',
        'instructor_id',
        'verified_by',
        'monitoring_date',
        'attendance_status',
        'remarks',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'monitoring_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RoomMonitoringHistory::class);
    }
}
