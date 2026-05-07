<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'employee_id',
        'date_of_birth',
        'gender',
        'nationality',
        'ni_number',
        'address',
        'postcode',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}