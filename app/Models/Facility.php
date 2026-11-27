<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity' => 'integer',
        ];
    }

    public function room(): HasOne
    {
        return $this->hasOne(Room::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(FacilityReservation::class);
    }

    public function activeReservations(): HasMany
    {
        return $this->reservations()
            ->where('status', 'approved');
    }
}
