<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClubApiController extends Controller
{
    /**
     * [R] Lấy danh sách các câu lạc bộ (kèm thông tin thể loại)
     * GET /api/clubs
     */
    public function index(Request $request): JsonResponse
    {
        $query = Club::query()->with('category');

        // Lọc theo thể loại nếu có ?category_id=...
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Tìm kiếm theo tên CLB nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where('name', 'like', "%{$keyword}%");
        }

        $clubs = $query->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách câu lạc bộ thành công',
            'total' => $clubs->count(),
            'data' => $clubs,
        ], 200);
    }

    /**
     * [R] Lấy chi tiết 1 câu lạc bộ theo ID
     * GET /api/clubs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $club = Club::query()->with('category')->find($id);

        if (!$club) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy câu lạc bộ này',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy chi tiết câu lạc bộ thành công',
            'data' => $club,
        ], 200);
    }

    /**
     * [C] Thêm mới câu lạc bộ
     * POST /api/clubs
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:clubs,name',
            'logo' => 'nullable|string|max:500',
            'category_id' => 'required|integer|exists:club_categories,id',
            'founded_date' => 'nullable|date',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên câu lạc bộ!',
            'name.unique' => 'Tên câu lạc bộ này đã tồn tại!',
            'category_id.required' => 'Vui lòng chọn thể loại cho CLB!',
            'category_id.exists' => 'Thể loại CLB được chọn không tồn tại trong hệ thống!',
            'founded_date.date' => 'Ngày thành lập không đúng định dạng ngày tháng (YYYY-MM-DD)!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $fields = [
            'name',
            'logo',
            'images',
            'category_id',
            'founded_date',
            'description',
            'missions',
            'management_structure',
            'regular_activities',
            'achievements',
            'membership_requirements',
            'recruitment_process',
        ];

        $club = Club::create($request->only($fields));

        $club->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm câu lạc bộ mới thành công!',
            'data' => $club,
        ], 201);
    }

    /**
     * [U] Cập nhật / Chỉnh sửa câu lạc bộ
     * PUT /api/clubs/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $club = Club::find($id);

        if (!$club) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy câu lạc bộ cần sửa',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:clubs,name,' . $id,
            'logo' => 'nullable|string|max:500',
            'category_id' => 'sometimes|required|integer|exists:club_categories,id',
            'founded_date' => 'nullable|date',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên câu lạc bộ không được để trống!',
            'name.unique' => 'Tên câu lạc bộ này đã được sử dụng!',
            'category_id.exists' => 'Thể loại CLB không hợp lệ!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $fields = [
            'name',
            'logo',
            'images',
            'category_id',
            'founded_date',
            'description',
            'missions',
            'management_structure',
            'regular_activities',
            'achievements',
            'membership_requirements',
            'recruitment_process',
        ];

        $club->update($request->only($fields));
        $club->load('category');


        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật câu lạc bộ thành công!',
            'data' => $club,
        ], 200);
    }

    /**
     * [D] Xóa câu lạc bộ
     * DELETE /api/clubs/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $club = Club::find($id);

        if (!$club) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy câu lạc bộ cần xóa',
            ], 404);
        }

        $club->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa câu lạc bộ thành công!',
        ], 200);
    }

    /**
     * [R] Lấy danh sách tất cả các Thể loại CLB kèm số lượng CLB
     * GET /api/club-categories
     */
    public function categories(): JsonResponse
    {
        $categories = ClubCategory::query()
            ->withCount('clubs')
            ->with('clubs')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách thể loại CLB thành công',
            'data' => $categories,
        ], 200);
    }

}
