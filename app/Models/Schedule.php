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

    protected $hidden = ['cooperative_id', 'locality_id', 'location_id'];
    protected $appends = ['cooperative', 'location', 'locality'];

    public function users(): BelongsToManyRelationship
    {
        return $this->belongsToManyRelationship(User::class, 'schedule_users');
    }

    public function getLocationAttribute(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function getLocalityAttribute(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function getCooperativeAttribute()
    {
        return $this->belongsTo(Cooperative::class)->select('name');
    }
}
