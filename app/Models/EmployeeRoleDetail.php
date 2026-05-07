<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRoleDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'role_type',
        'data',
         'data1',
         'secondary_skills',
    ];

    protected $casts = [
        'data' => 'array',
        'data1' => 'array',
        'secondary_skills' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}