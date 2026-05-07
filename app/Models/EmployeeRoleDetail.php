<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRoleDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'role_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}