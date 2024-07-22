<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    const UPLOAD_PATH = "uploads/banner";

    protected $fillable = [
        'order',
        'name',
        'description',
        'image',
        'status',
    ];
}
