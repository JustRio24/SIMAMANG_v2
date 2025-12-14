<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PdfController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Internship Applications
    Route::resource('internships', InternshipApplicationController::class);
    Route::post('/internships/{internship}/upload-response', [InternshipApplicationController::class, 'uploadResponse'])
        ->name('internships.upload-response');
    
    // Approvals
    Route::post('/internships/{internship}/verify', [ApprovalController::class, 'verify'])
        ->name('internships.verify');
    Route::post('/internships/{internship}/approve', [ApprovalController::class, 'approve'])
        ->name('internships.approve');
    Route::post('/internships/{internship}/generate-letter', [ApprovalController::class, 'generateLetter'])
        ->name('internships.generate-letter');
    
    // PDF Generation
    Route::get('/internships/{internship}/letter', [PdfController::class, 'generateLetter'])
        ->name('pdf.letter');
    Route::get('/internships/{internship}/letter/download', [PdfController::class, 'downloadLetter'])
        ->name('pdf.letter.download');
    
    // Chatbot MAMANG
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
});