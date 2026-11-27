<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacilityReservation extends Model
{
    protected $fillable = [
        'facility_id',
        'event_name',
        'description',
        'reservation_date',
        'start_time',
        'end_time',
        'requesting_office',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('reservation_date', '>=', today());
    }

    public function getDisplayStatusAttribute(): string
{
    if ($this->status !== 'approved') {
        return ucfirst($this->status);
    }

    $start = \Carbon\Carbon::parse(
        $this->reservation_date->toDateString() . ' ' . $this->start_time
    );

    $end = \Carbon\Carbon::parse(
        $this->reservation_date->toDateString() . ' ' . $this->end_time
    );

    $now = now();

    if ($now->lt($start)) {
        return 'Upcoming';
    }

    if ($now->lt($end)) {
        return 'Ongoing';
    }

    return 'Completed';
}
}
