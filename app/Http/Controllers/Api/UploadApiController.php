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
     * Hỗ trợ tải 1 ảnh (field 'file') hoặc nhiều ảnh cùng lúc (field 'files')
     */
    public function upload(Request $request): JsonResponse
    {
        $folder = $request->input('folder', 'general');
        $validFolders = ['activities', 'clubs', 'people', 'banners', 'avatars', 'general', 'documents'];
        if (!in_array($folder, $validFolders)) {
            $folder = 'general';
        }

        $destinationPath = public_path('uploads/' . $folder);
        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0777, true);
        }

        // Trường hợp 1: Tải nhiều ảnh cùng lúc (field 'files')
        if ($request->hasFile('files')) {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array',
                'files.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480', // 20MB / file
            ], [
                'files.*.image' => 'File tải lên phải là hình ảnh hợp lệ!',
                'files.*.mimes' => 'Hỗ trợ định dạng: JPG, JPEG, PNG, GIF, WEBP, SVG!',
                'files.*.max' => 'Dung lượng mỗi ảnh tối đa cho phép là 20MB!',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            $uploadedUrls = [];
            $uploadedData = [];

            try {
                foreach ($request->file('files') as $file) {
                    $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
                    $filename = time() . '_' . Str::random(10) . '.' . $ext;
                    $file->move($destinationPath, $filename);

                    $relativePath = '/uploads/' . $folder . '/' . $filename;
                    $uploadedUrls[] = $relativePath;
                    $uploadedData[] = [
                        'file_name' => $filename,
                        'relative_path' => $relativePath,
                        'url' => $relativePath,
                        'full_url' => url($relativePath),
                    ];
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi lưu file vào máy chủ: ' . $e->getMessage(),
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Tải lên ' . count($uploadedUrls) . ' ảnh thành công!',
                'data' => [
                    'urls' => $uploadedUrls,
                    'items' => $uploadedData,
                    'url' => $uploadedUrls[0] ?? null,
                ],
            ], 200);
        }

        // Trường hợp 2: Tải 1 ảnh (field 'file')
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480', // Tối đa 20MB
        ], [
            'file.required' => 'Vui lòng chọn 1 file ảnh từ máy tính!',
            'file.file' => 'Dữ liệu gửi lên phải là một tập tin!',
            'file.image' => 'File tải lên phải là hình ảnh hợp lệ!',
            'file.mimes' => 'Định dạng ảnh được hỗ trợ: JPG, JPEG, PNG, GIF, WEBP, SVG!',
            'file.max' => 'Dung lượng ảnh tối đa cho phép là 20MB!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $filename = time() . '_' . Str::random(10) . '.' . $extension;

            $file->move($destinationPath, $filename);

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
                    'size' => @filesize($destinationPath . '/' . $filename) ?: 0,
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi lưu file vào máy chủ: ' . $e->getMessage(),
            ], 500);
        }
    }
}
