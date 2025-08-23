<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    $content = \App\Services\ContentService::getAllContent();
    return Inertia::render('Welcome', compact('content'));
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'content'])->name('admin.dashboard');
    Route::get('/content', [AdminController::class, 'content'])->name('admin.content');
    Route::post('/content', [AdminController::class, 'updateContent'])->name('admin.content.update');
    Route::post('/content/save', [AdminController::class, 'updateContent'])->name('admin.content.save');
    Route::post('/upload-image', [AdminController::class, 'uploadImage'])->name('admin.upload.image');
    Route::delete('/delete-image', [AdminController::class, 'deleteImage'])->name('admin.delete.image');
});
