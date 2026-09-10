<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'is_active',
        'movement_type',
        'activity_type',
        'location',
        'target_audience',
        'participants',
        'summary_content',
        'significance',
        'result',
        'objective',
        'cooperation',
        'value',
        'comment',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'comment' => 'array',
    ];

    public function getThumbnailAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return url($value);
    }
}