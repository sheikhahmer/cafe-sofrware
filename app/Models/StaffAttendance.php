<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends Model
{
    protected $fillable = [
        'designation_id',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}

