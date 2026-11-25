<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuildingAnnouncement extends Model
{
    protected $fillable = [
        'building_id',
        'building_code',
        'building_name',
        'title',
        'message',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }
    public function reads(): HasMany
{
    return $this->hasMany(BuildingAnnouncementRead::class);
}
}
