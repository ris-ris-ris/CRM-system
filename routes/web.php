<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return \Illuminate\Support\Facades\Auth::user()->is_admin 
            ? redirect()->route('admin.index') 
            : redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/companies', fn() => view('crm.companies'))->name('companies.page');
    Route::get('/contacts', fn() => view('crm.contacts'))->name('contacts.page');
    Route::get('/deals', fn() => view('crm.deals'))->name('deals.page');
    Route::get('/kanban', fn() => view('crm.kanban'))->name('kanban.page');
    Route::get('/activities', fn() => view('crm.activities'))->name('activities.page');
    Route::get('/calendar', fn() => view('crm.calendar'))->name('calendar.page');
    Route::get('/reports', fn() => view('crm.reports'))->name('reports.page');
    Route::get('/notes', fn() => view('crm.notes'))->name('notes.page');
    Route::get('/import', [App\Http\Controllers\ImportController::class, 'index'])->name('import.index');
    Route::post('/import', [App\Http\Controllers\ImportController::class, 'store'])->name('import.store');
    Route::post('/import/generate', [App\Http\Controllers\GenerateDataController::class, 'generate'])->name('import.generate');
    
    // Chat routes
    Route::get('/api/chat/messages', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.messages');
    Route::post('/api/chat/messages', [App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
        Route::patch('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'destroy'])->name('users.destroy');
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/clear', [App\Http\Controllers\AdminController::class, 'clearData'])->name('settings.clear');
        Route::get('/database', [App\Http\Controllers\AdminController::class, 'database'])->name('database');
        Route::post('/database/query', [App\Http\Controllers\AdminController::class, 'executeQuery'])->name('database.query');
        Route::get('/database/table', [App\Http\Controllers\AdminController::class, 'getTableData'])->name('database.table');
    });
});

require __DIR__.'/auth.php';
