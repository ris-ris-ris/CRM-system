<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{CompanyController, ContactController, DealController, StageController, ActivityController, NoteController, FileAttachmentController};

Route::middleware(['web', 'auth'])->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('deals', DealController::class);
    Route::apiResource('stages', StageController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('notes', NoteController::class);
    Route::apiResource('files', FileAttachmentController::class);
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
    
    // Email routes
    Route::get('emails', [\App\Http\Controllers\EmailController::class, 'getEmails']);
    Route::post('emails/send', [\App\Http\Controllers\EmailController::class, 'sendEmail']);
    Route::post('emails/sync', [\App\Http\Controllers\EmailController::class, 'syncIncoming']);
    Route::get('deals/{deal}/emails', [\App\Http\Controllers\EmailController::class, 'getDealEmails']);
});
