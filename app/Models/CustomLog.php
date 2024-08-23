<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module',
        'severity',
        'file',
        'line',
        'url',
        'method',
        'message',
        'ip_address',
        'description',
        'metadata',
        'is_read',
    ];
}
