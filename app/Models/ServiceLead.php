<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLead extends Model
{
    protected $fillable = [
        'kind',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];
}
