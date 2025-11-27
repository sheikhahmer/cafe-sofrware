<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    protected $fillable = [
        'name',
        'contact_no',
        'cnic',
        'salary',
    ];

    public function salaryPayments(): HasMany
    {
        return $this->hasMany(DesignationSalaryPayment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function paidThisMonth(): float
    {
        return (float) $this->salaryPayments()
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
    }

    public function remainingThisMonth(): float
    {
        $remaining = (float) $this->salary - $this->paidThisMonth();

        return $remaining > 0 ? $remaining : 0.0;
    }
}
