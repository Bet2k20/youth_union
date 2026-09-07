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
        'founded_date',
        'category_id',
        'description',
    ];

    protected $casts = [
        'founded_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ClubCategory::class, 'category_id');
    }
}