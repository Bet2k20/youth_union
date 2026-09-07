<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadApiController extends Controller
{
    /**
     * [UPLOAD] Tải file ảnh từ máy tính lên Server
     * POST /api/upload
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120', // Tối đa 5MB
            'folder' => 'nullable|string|in:activities,clubs,people,banners,avatars,general',
        ], [
            'file.required' => 'Vui lòng chọn 1 file ảnh từ máy tính!',
            'file.image' => 'File tải lên phải là hình ảnh hợp lệ!',
            'file.mimes' => 'Định dạng ảnh được hỗ trợ: JPG, JPEG, PNG, GIF, WEBP, SVG!',
            'file.max' => 'Dung lượng ảnh tối đa cho phép là 5MB!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tải lên không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('file');
        $folder = $request->input('folder', 'general');

        // Tạo thư mục lưu trữ trong public/uploads/{folder} nếu chưa tồn tại
        $destinationPath = public_path('uploads/' . $folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Đặt tên file ngẫu nhiên chống trùng lặp
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;

        // Di chuyển file vào thư mục đích
        $file->move($destinationPath, $filename);

        // Đường dẫn tương đối và đường dẫn tuyệt đối
        $relativePath = '/uploads/' . $folder . '/' . $filename;
        $fullUrl = url($relativePath);

        return response()->json([
            'status' => 'success',
            'message' => 'Tải ảnh lên máy chủ thành công!',
            'data' => [
                'file_name' => $filename,
                'relative_path' => $relativePath,
                'url' => $relativePath,
                'full_url' => $fullUrl,
                'size' => filesize($destinationPath . '/' . $filename),
            ],
        ], 200);
    }
}
