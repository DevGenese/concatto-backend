<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    protected $fillable = [
        'name',
        'unity',
    ];
    /**
     * Get all expenses for this expense type.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
