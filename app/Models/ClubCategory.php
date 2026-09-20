<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = [
        'slug',
    ];

    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->name);
    }

    public function clubs(): HasMany
    {
        return $this->hasMany(Club::class, 'category_id');
    }
}