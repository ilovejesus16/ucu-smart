<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
    ];

    /**
     * Get the user who performed the activity.
     */
    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
}
