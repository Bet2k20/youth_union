<?php

namespace App\Http\Controllers;
use App\Models\Activity; // 1. Gọi Model Activity để kết nối bảng activities
use Illuminate\Http\Request;
class ActivityController extends Controller
{
    // Hàm lấy danh sách tất cả hoạt động
    public function index()
    {
        // 2. Lấy tất cả hoạt động có is_active = 1, sắp xếp bài mới nhất lên đầu
        $activities = Activity::where('is_active', true)
            ->latest('id')
            ->get();
        // 3. Gửi biến $activities sang file giao diện (View) có tên là 'activities.index'
        return view('activities.index', compact('activities'));
    }
}