<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'account_description',
        'product',
        'debit',
        'credit',
    ];
}
