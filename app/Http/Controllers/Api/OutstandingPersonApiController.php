<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutstandingPerson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutstandingPersonApiController extends Controller
{
    /**
     * [R] Lấy danh sách Gương mặt tiêu biểu / Cán bộ Đoàn xuất sắc / Sinh viên tiêu biểu
     * GET /api/outstanding-people
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Hỗ trợ truy vấn chi tiết 1 người qua query param ?id=...
        if ($request->filled('id')) {
            return $this->show((int) $request->input('id'));
        }

        $query = OutstandingPerson::query();

        // Mặc định lấy bài đang active, trừ khi có ?all=1
        if (!$request->boolean('all')) {
            $query->where('is_active', true);
        }

        // Lọc theo nhóm vai trò / danh hiệu: role_group, role, hoặc type
        $rawRole = $request->input('role_group') ?? $request->input('role') ?? $request->input('type');
        if ($rawRole) {
            $roleNormalized = strtoupper(trim($rawRole));
            if (in_array($roleNormalized, ['CAN_BO', 'CADRE', 'CADRES', 'CAN_BO_DOAN', 'BI_THU_DOAN', 'CAN_BO_TIEU_BIEU'])) {
                $query->where('role_group', 'BI_THU_DOAN');
            } elseif (in_array($roleNormalized, ['SINH_VIEN', 'STUDENT', 'STUDENTS', 'DOAN_VIEN', 'DOAN_VIEN_XUAT_SAC', 'SINH_VIEN_TIEU_BIEU'])) {
                $query->where('role_group', 'DOAN_VIEN');
            } elseif ($roleNormalized === 'BGD') {
                $query->where('role_group', 'BGD');
            } else {
                $query->where('role_group', $rawRole);
            }
        }

        // Tìm kiếm theo tên nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where('name', 'like', "%{$keyword}%");
        }

        $people = $query->orderBy('id', 'desc')->get();

        // Nếu Frontend muốn trả về cấu trúc chia nhóm sẵn (?grouped=1)
        if ($request->boolean('grouped')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách gương mặt tiêu biểu theo nhóm thành công',
                'data' => [
                    'cadres' => $people->where('role_group', 'BI_THU_DOAN')->values(),
                    'students' => $people->where('role_group', 'DOAN_VIEN')->values(),
                    'leaders' => $people->where('role_group', 'BGD')->values(),
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách gương mặt tiêu biểu thành công',
            'total' => $people->count(),
            'data' => $people,
        ], 200);
    }

    /**
     * [R] API chuyên biệt lấy danh sách Cán bộ Đoàn tiêu biểu
     * GET /api/cadres hoặc GET /api/can-bo-tieu-bieu
     */
    public function cadres(Request $request): JsonResponse
    {
        $request->merge(['role_group' => 'BI_THU_DOAN']);
        return $this->index($request);
    }

    /**
     * [R] API chuyên biệt lấy danh sách Sinh viên tiêu biểu
     * GET /api/students hoặc GET /api/sinh-vien-tieu-bieu
     */
    public function students(Request $request): JsonResponse
    {
        $request->merge(['role_group' => 'DOAN_VIEN']);
        return $this->index($request);
    }

    /**
     * [R] Lấy chi tiết 1 gương mặt tiêu biểu theo ID
     * GET /api/outstanding-people/{id}
     */
    public function show(int $id): JsonResponse
    {
        $person = OutstandingPerson::find($id);

        if (!$person) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy thông tin gương mặt tiêu biểu này',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy chi tiết gương mặt tiêu biểu thành công',
            'data' => $person,
        ], 200);
    }

    /**
     * [C] Thêm mới gương mặt tiêu biểu
     * POST /api/outstanding-people
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:500',
            'role_group' => 'required|in:BGD,BI_THU_DOAN,DOAN_VIEN',
            'class_unit' => 'nullable|string|max:255',
            'achievement' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên!',
            'role_group.required' => 'Vui lòng chọn nhóm danh hiệu (BGD, BI_THU_DOAN, DOAN_VIEN)!',
            'role_group.in' => 'Nhóm danh hiệu phải là một trong các giá trị: BGD, BI_THU_DOAN, DOAN_VIEN',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $person = OutstandingPerson::create([
            'name' => $request->name,
            'avatar' => $request->avatar,
            'role_group' => $request->role_group,
            'class_unit' => $request->class_unit,
            'achievement' => $request->achievement,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm gương mặt tiêu biểu thành công!',
            'data' => $person,
        ], 201);
    }

    /**
     * [U] Cập nhật gương mặt tiêu biểu
     * PUT /api/outstanding-people/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $person = OutstandingPerson::find($id);

        if (!$person) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy thông tin cần sửa',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'avatar' => 'nullable|string|max:500',
            'role_group' => 'sometimes|required|in:BGD,BI_THU_DOAN,DOAN_VIEN',
            'class_unit' => 'nullable|string|max:255',
            'achievement' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Họ và tên không được để trống!',
            'role_group.in' => 'Nhóm danh hiệu không hợp lệ!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $person->update($request->only(['name', 'avatar', 'role_group', 'class_unit', 'achievement', 'is_active']));

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật gương mặt tiêu biểu thành công!',
            'data' => $person,
        ], 200);
    }

    /**
     * [D] Xóa gương mặt tiêu biểu
     * DELETE /api/outstanding-people/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $person = OutstandingPerson::find($id);

        if (!$person) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy thông tin cần xóa',
            ], 404);
        }

        $person->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thông tin thành công!',
        ], 200);
    }
}
