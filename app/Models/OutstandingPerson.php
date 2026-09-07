<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutstandingPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'role_group',
        'class_unit',
        'achievement',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}