<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToManyRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'cooperative_id',
        'locality_id',
        'advice_type',
        'observations',
        'location_id',
        'finished'
    ];

    protected $hidden = [
        'cooperative_id',
        'locality_id',
        'location_id',
        'pivot',
    ];
    protected $appends = [
        'cooperative',
        'location',
        'locality',
    ];

    public function users(): BelongsToManyRelationship
    {
        return $this->belongsToManyRelationship(User::class, 'schedule_users');
    }

    public function getLocationAttribute()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->value('name');
    }

    public function getLocalityAttribute()
    {
        return $this->belongsTo(Locality::class, 'locality_id', 'id')->value('city');
    }

    public function getCooperativeAttribute()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id', )->value('name');
    }
}
