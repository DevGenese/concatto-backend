<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'cooperative_id',
        'locality_id',
        'advice_type',
        'observations',
        'location_id',
        'finished'
    ];

    protected $hidden = [
        'pivot',
    ];
    protected $appends = [
        'cooperative',
        'location',
        'locality',
        'formattedDate'
    ];

    protected $casts = [
        'date' => 'datetime',
        'finished' => 'boolean',
    ];

    protected function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)->format('H:i') . " " . $this->date->format('d/m/Y');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'schedule_users');
    }

    public function getUserNamesAttribute(): string
    {
        return $this->users->pluck('name')->join(', ');
    }

    public function getLocationAttribute()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->value('name');
    }

    public function getLocalityAttribute()
    {
        return
            $this->belongsTo(Locality::class, 'locality_id', 'id')->value('city')
            . '/' .
            $this->belongsTo(Locality::class, 'locality_id', 'id')->value('UF');
    }

    public function getCooperativeAttribute()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id', )->value('name');
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
