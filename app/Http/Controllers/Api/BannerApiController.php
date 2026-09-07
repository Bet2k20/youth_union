<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerApiController extends Controller
{
    /**
     * [R] Lấy danh sách banner ảnh bìa
     * GET /api/banners
     */
    public function index(Request $request): JsonResponse
    {
        $query = Banner::query();

        // Mặc định lấy banner đang active (cho sinh viên/khách), trừ khi có ?all=1 (cho Admin)
        if (!$request->boolean('all')) {
            $query->where('is_active', true);
        }

        // Sắp xếp theo thứ tự ưu tiên (order bé lên trước), sau đó theo ID mới nhất
        $banners = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách banner thành công',
            'total' => $banners->count(),
            'data' => $banners,
        ], 200);
    }

    /**
     * [R] Xem chi tiết 1 banner
     * GET /api/banners/{id}
     */
    public function show(int $id): JsonResponse
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy banner này',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy chi tiết banner thành công',
            'data' => $banner,
        ], 200);
    }

    /**
     * [C] Thêm mới banner
     * POST /api/banners
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image_url' => 'required|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Vui lòng nhập tên/tiêu đề banner!',
            'image_url.required' => 'Vui lòng nhập đường link ảnh banner!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $banner = Banner::create([
            'title' => $request->title,
            'image_url' => $request->image_url,
            'link_url' => $request->link_url,
            'order' => $request->input('order', 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm banner mới thành công!',
            'data' => $banner,
        ], 201);
    }

    /**
     * [U] Sửa / Cập nhật banner
     * PUT /api/banners/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy banner cần sửa',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'image_url' => 'sometimes|required|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $banner->update($request->only(['title', 'image_url', 'link_url', 'order', 'is_active']));

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật banner thành công!',
            'data' => $banner,
        ], 200);
    }

    /**
     * [D] Xóa banner
     * DELETE /api/banners/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy banner cần xóa',
            ], 404);
        }

        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa banner thành công!',
        ], 200);
    }
}
