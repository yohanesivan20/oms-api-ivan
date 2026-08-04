<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'service_name',
        'endpoint',
        'method',
        'request',
        'response',
        'status_code',
        'is_success',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
        'is_success' => 'boolean',
        'created_at' => 'datetime',
    ];
}
