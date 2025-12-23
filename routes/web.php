<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\NewsController;

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

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
    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])
        ->middleware('throttle:10,1') // Maksimal 10 pesan per menit
        ->name('chatbot.send');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');

    // Templates
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');
    Route::patch('/templates/{template}/toggle', [TemplateController::class, 'toggleStatus'])->name('templates.toggle');
    Route::delete('/templates/{template}', [TemplateController::class, 'destroy'])->name('templates.destroy');
    
    // Revision by pejabat
    Route::post('/internships/{internship}/revise', [ApprovalController::class, 'revise'])->name('internships.revise');
    
    // Generated Documents Download/Upload
    Route::get('/internships/{internship}/document/{document}/download', [InternshipApplicationController::class, 'downloadGeneratedDocument'])
        ->name('internships.document.download');
        Route::post(
            '/internships/{internship}/documents/upload',
            [InternshipApplicationController::class, 'uploadGeneratedDocuments']
        )->name('internships.documents.upload');
        
    

    Route::get('/internships/{internship}/regen', [\App\Http\Controllers\ApprovalController::class, 'regen'])
        ->name('internships.regen');
    
});