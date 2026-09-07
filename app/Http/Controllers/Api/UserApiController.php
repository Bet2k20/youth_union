<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    /**
     * [R] Lấy danh sách tài khoản
     * GET /api/users
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Lọc theo vai trò nếu có ?role=...
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Tìm kiếm theo tên hoặc email nếu có ?search=...
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách tài khoản thành công',
            'total' => $users->count(),
            'data' => $users,
        ], 200);
    }

    /**
     * [R] Xem chi tiết 1 tài khoản
     * GET /api/users/{id}
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy tài khoản này',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    /**
     * [C] Thêm tài khoản mới
     * POST /api/users
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,editor,student',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên!',
            'email.required' => 'Vui lòng nhập email!',
            'email.unique' => 'Địa chỉ email này đã được sử dụng!',
            'password.required' => 'Vui lòng nhập mật khẩu khởi tạo!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            'role.in' => 'Vai trò phải là một trong các giá trị: admin, editor, student!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo tài khoản mới thành công!',
            'data' => $user,
        ], 201);
    }

    /**
     * [U] Cập nhật thông tin tài khoản
     * PUT /api/users/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy tài khoản cần sửa',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|required|in:admin,editor,student',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = $request->only(['name', 'email', 'role', 'phone', 'is_active']);

        // Nếu có nhập mật khẩu mới thì cập nhật
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật tài khoản thành công!',
            'data' => $user,
        ], 200);
    }

    /**
     * [D] Xóa tài khoản
     * DELETE /api/users/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy tài khoản cần xóa',
            ], 404);
        }

        // Không cho phép tự xóa tài khoản của chính mình khi đang đăng nhập
        if ($request->user() && $request->user()->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không thể tự xóa tài khoản của chính mình!',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa tài khoản thành công!',
        ], 200);
    }
}
