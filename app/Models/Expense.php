<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Expense extends Model
{
    protected $fillable = [
        'expense_type_id',
        'schedule_id',
        'user_id',
        'amount',
        'observation',
        'attachment',
        'created_at'
    ];

    protected $appends = [
        'attachment_url',
        'expense_type',
        'user_name',
    ];

    protected $hidden = [
        'attachment'
    ];

    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment) {
            return null;
        }

        return "https://api.concatto-consultoria.org/public/app/public/" . $this->attachment;
    }

    /**
     * Get the expense type associated with the expense.
     */
    public function expenseType($columns): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class)->select($columns ?? ['*']);
    }

    public function getExpenseTypeAttribute()
    {
        return $this->expenseType(['name', 'unity'])->first();
    }

    /**
     * Get the user that owns the expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserNameAttribute(): string
    {
        return $this->user->value('name');
    }
}
