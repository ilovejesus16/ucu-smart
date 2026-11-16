<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollegeRoomAssignment extends Model
{
    protected $fillable = [
    'college_id',
    'room_id',
    'academic_year_id',
    'room_type',
    'is_primary',
    'remarks',
];

protected function casts(): array
{
    return [
        'is_primary' => 'boolean',
    ];
}

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
