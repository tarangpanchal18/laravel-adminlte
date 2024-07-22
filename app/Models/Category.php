<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    const UPLOAD_PATH = "uploads/category";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'image',
        'status',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Category::class , 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class , 'parent_id');
    }
}
