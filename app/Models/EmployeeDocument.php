<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'file_path',
        'expiry_date',
        'verified',
    ];

    /**
     * CAST EXPIRY DATE
     */
    protected $casts = [
        'expiry_date' => 'date',
        'verified' => 'boolean',
    ];

    /**
     * DOCUMENT BELONGS TO EMPLOYEE
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}