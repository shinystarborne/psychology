<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function content()
    {
        $contents = \App\Models\SiteContent::all()->groupBy('section');
        return Inertia::render('Admin/Content', compact('contents'));
    }

    public function updateContent(Request $request)
    {
        $validated = $request->validate([
            'contents' => 'required|array',
            'contents.*.key' => 'required|string',
            'contents.*.value' => 'required',
            'contents.*.type' => 'string|in:text,json,html'
        ]);

        foreach ($validated['contents'] as $content) {
            \App\Models\SiteContent::setValue(
                $content['key'],
                $content['value'],
                $content['type'] ?? 'text',
                $content['section'] ?? null,
                $content['description'] ?? null
            );
        }

        return back()->with('success', 'Контент успешно обновлен!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'section' => 'required|string'
        ]);

        try {
            $image = $request->file('image');
            $section = $request->input('section');
            
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Изображение не получено'
                ], 400);
            }
            
            // Generate unique filename
            $filename = $section . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Determine subdirectory based on section
            $subDirectory = $section === 'gallery' ? 'diplomas' : 'images';
            
            // Create directory if it doesn't exist
            $assetsPath = public_path('assets/' . $subDirectory);
            if (!file_exists($assetsPath)) {
                if (!mkdir($assetsPath, 0777, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Не удалось создать директорию: ' . $assetsPath
                    ], 500);
                }
            }
            
            // Check if directory is writable
            if (!is_writable($assetsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Директория не доступна для записи: ' . $assetsPath
                ], 500);
            }
            
            // Move file to appropriate directory
            if (!$image->move($assetsPath, $filename)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось сохранить файл в директорию: ' . $assetsPath
                ], 500);
            }
            
            // Generate URL for the uploaded image
            $url = '/assets/' . $subDirectory . '/' . $filename;
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => 'assets/' . $subDirectory . '/' . $filename,
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке изображения: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'image_url' => 'required|string'
        ]);

        try {
            $imageUrl = $request->input('image_url');
            
            // Extract filename from URL (e.g., /assets/images/about_123456_abc.jpg -> about_123456_abc.jpg)
            $filename = basename($imageUrl);
            
            // Determine subdirectory from URL path
            $urlPath = parse_url($imageUrl, PHP_URL_PATH);
            if (strpos($urlPath, '/assets/diplomas/') !== false) {
                $subDirectory = 'diplomas';
            } else {
                $subDirectory = 'images';
            }
            
            // Construct full path to the image
            $imagePath = public_path('assets/' . $subDirectory . '/' . $filename);
            
            // Delete the file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Изображение успешно удалено'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении изображения: ' . $e->getMessage()
            ], 500);
        }
    }
}
