<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Alert extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    // ISO-8601 in the DB column, matching the shape the legacy seeder imports.
    // (JSON output uses Laravel's toJSON() regardless of this.)
    protected $dateFormat = 'Y-m-d\TH:i:sP';

    protected $fillable = [
        'type', 'facility_id', 'room', 'message', 'status',
        'recipients', 'responders', 'acknowledged_by', 'acknowledged_at',
    ];

    protected $casts = [
        'responders' => 'array',
        'acknowledged_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $alert) {
            $alert->id ??= 'ALT-'.Str::ulid();
            $alert->responders ??= [];
        });
    }
}
