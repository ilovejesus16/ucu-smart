<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomMonitoringHistory extends Model
{
    protected $fillable = [
        'room_monitoring_id',
        'changed_by',
        'old_status',
        'new_status',
        'old_remarks',
        'new_remarks',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(
            RoomMonitoring::class,
            'room_monitoring_id'
        );
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
