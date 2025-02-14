<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_type_id',
        'schedule_id',
        'user_id',
        'amount',
        'observation',
        'attachment',
    ];
}
