<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignationSalaryPayment extends Model
{
    protected $fillable = [
        'designation_id',
        'amount',
        'paid_at',
        'note',
    ];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}


