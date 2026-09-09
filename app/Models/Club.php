<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'images',
        'founded_date',
        'category_id',
        'description',
    ];

    protected $casts = [
        'founded_date' => 'date',
        'images' => 'array',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(ClubCategory::class, 'category_id');
    }

    public function getLogoAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return url($value);
    }

    public function getImagesAttribute($value): array
    {
        if (!$value) return [];
        $imgs = is_string($value) ? json_decode($value, true) : $value;
        if (!is_array($imgs)) return [];
        return array_map(function ($img) {
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }
            return url($img);
        }, $imgs);
    }
}