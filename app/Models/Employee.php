<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'postcode',
        'date_of_birth',
        'gender',
        'nationality',
        'ni_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'status',
    ];

    /**
     * USER RELATIONSHIP (LOGIN ACCOUNT)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ONE EMPLOYEE HAS ONE PROFILE (EXTENDED DATA)
     */
    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * ONE EMPLOYEE HAS MANY DOCUMENTS
     */
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function complianceRecords()
    {
        return $this->hasMany(ComplianceRecords::class);
}
}