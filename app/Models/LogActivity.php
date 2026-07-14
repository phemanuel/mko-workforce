<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    //
    protected $fillable = [
        'user_id',
        'ip_address',
        'activity',
        'activity_date',
    ];
}
