<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\CompanyManager;
use App\Livewire\CompanyDirectory;
use Illuminate\Support\Facades\Route;

Route::get('/', CompanyDirectory::class)->name('home');
Route::get('/empresas/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {
    Route::get('/empresas', CompanyManager::class)->name('companies.index');
    Route::get('/categorias', CategoryManager::class)->name('categories.index');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
