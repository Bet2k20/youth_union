<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityApiController extends Controller
{
    /**
     * [R] Lấy danh sách hoạt động / tin tức (hỗ trợ phân trang và tìm kiếm)
     * GET /api/activities
     */
    public function index(Request $request): JsonResponse
    {
        $query = Activity::query();

        // Mặc định lấy bài đang active, nếu có truyền ?all=1 thì lấy tất cả (dành cho trang Admin)
        if (!$request->boolean('all')) {
            $query->where('is_active', true);
        }

        // Tìm kiếm theo tiêu đề nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where('title', 'like', "%{$keyword}%");
        }

        // Sắp xếp mới nhất lên đầu
        $query->latest('id');

        // Nếu có truyền ?limit=... thì lấy theo số lượng cố định, nếu không thì phân trang
        if ($request->filled('limit')) {
            $activities = $query->take((int) $request->input('limit'))->get();
        } else {
            $perPage = (int) $request->input('per_page', 10);
            $activities = $query->paginate($perPage);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách hoạt động thành công',
            'data' => $activities,
        ], 200);
    }

    /**
     * [R] Lấy chi tiết 1 hoạt động theo ID
     * GET /api/activities/{id}
     */
    public function show(int $id): JsonResponse
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy hoạt động này',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy chi tiết hoạt động thành công',
            'data' => $activity,
        ], 200);
    }

    /**
     * [C] Thêm mới hoạt động
     * POST /api/activities
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề hoạt động!',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity = Activity::create([
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $request->thumbnail,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm hoạt động mới thành công!',
            'data' => $activity,
        ], 201);
    }

    /**
     * [U] Cập nhật / Chỉnh sửa hoạt động
     * PUT /api/activities/{id} hoặc POST /api/activities/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy hoạt động cần sửa',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Tiêu đề hoạt động không được để trống!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity->update($request->only(['title', 'content', 'thumbnail', 'is_active']));

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật hoạt động thành công!',
            'data' => $activity,
        ], 200);
    }

    /**
     * [D] Xóa hoạt động
     * DELETE /api/activities/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy hoạt động cần xóa',
            ], 404);
        }

        $activity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa hoạt động thành công!',
        ], 200);
    }
}
