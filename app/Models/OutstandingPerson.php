<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutstandingPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'avatar',
        'role_group',
        'class_unit',
        'achievement',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'role_label',
        'class_name',
        'class',
    ];

    /**
     * Tự động chuyển đổi đường dẫn ảnh tương đối thành URL đầy đủ
     */
    public function getAvatarAttribute($value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return url($value);
    }

    /**
     * Nhãn tiếng Việt hiển thị danh hiệu / vai trò
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role_group) {
            'BGD' => 'Ban Giám Đốc / Đảng Ủy',
            'BI_THU_DOAN' => 'Cán bộ Đoàn tiêu biểu',
            'DOAN_VIEN' => 'Sinh viên tiêu biểu',
            default => 'Gương mặt tiêu biểu',
        };
    }

    /**
     * Alias class_name cho class_unit (để Frontend dùng trực tiếp)
     */
    public function getClassNameAttribute(): ?string
    {
        return $this->class_unit;
    }

    /**
     * Alias class cho class_unit
     */
    public function getClassAttribute(): ?string
    {
        return $this->class_unit;
    }
}