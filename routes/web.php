<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExitSurveyController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\MicrosoftAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSurveyController;
use Illuminate\Support\Facades\Route;

// ── Microsoft OAuth SSO ────────────────────────────────────────────────────────
Route::get('/auth/microsoft', [MicrosoftAuthController::class, 'redirect'])->name('auth.microsoft');
Route::get('/auth/microsoft/callback', [MicrosoftAuthController::class, 'callback'])->name('auth.microsoft.callback');

// ── Public: Employee Survey (token-based, password protected) ──────────────────
Route::prefix('exit-survey')->name('survey.')->group(function () {
    Route::get('/{token}', [PublicSurveyController::class, 'show'])->name('show');
    Route::post('/{token}/auth', [PublicSurveyController::class, 'authenticate'])->name('auth');
    Route::post('/{token}', [PublicSurveyController::class, 'submit'])->name('submit');
    Route::get('/done/success', fn () => view('survey.success'))->name('success');
});

// ── Redirect root to admin dashboard when logged in ──────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

// ── Admin Panel (authenticated) ───────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Surveys table
    Route::get('/surveys/check-duplicate', [ExitSurveyController::class, 'checkDuplicate'])->name('surveys.check-duplicate');
    Route::get('/surveys', [ExitSurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/{exitSurvey}', [ExitSurveyController::class, 'show'])->name('surveys.show');
    Route::post('/surveys', [ExitSurveyController::class, 'store'])->name('surveys.store');
    Route::post('/surveys/{exitSurvey}/resend', [ExitSurveyController::class, 'resend'])->name('surveys.resend');
    Route::post('/surveys/{exitSurvey}/renew', [ExitSurveyController::class, 'renew'])->name('surveys.renew');
    Route::post('/surveys/{exitSurvey}/revoke', [ExitSurveyController::class, 'revoke'])->name('surveys.revoke');
    Route::get('/surveys/{exitSurvey}/pdf', [ExitSurveyController::class, 'downloadPdf'])->name('surveys.pdf');
    Route::get('/surveys/{exitSurvey}/word', [ExitSurveyController::class, 'downloadWord'])->name('surveys.word');

    // Analytics
    Route::get('/analytics', AnalyticsController::class)->name('analytics');

    // Template Management
    Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/download', [TemplateController::class, 'downloadDocx'])->name('templates.download');

    // Settings (super_admin only — enforced in controller middleware)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/hr-managers', [SettingsController::class, 'storeHrManager'])->name('settings.hr-managers.store');
    Route::post('/settings/hr-managers/microsoft', [SettingsController::class, 'storeMicrosoftHrManager'])->name('settings.hr-managers.store-microsoft');
    Route::get('/settings/azure-users/search', [SettingsController::class, 'searchAzureUsers'])->name('settings.azure-users.search');
    Route::patch('/settings/hr-managers/{user}', [SettingsController::class, 'updateHrManager'])->name('settings.hr-managers.update');
    Route::delete('/settings/hr-managers/{user}', [SettingsController::class, 'destroyHrManager'])->name('settings.hr-managers.destroy');
    Route::post('/settings/companies', [SettingsController::class, 'storeCompany'])->name('settings.companies.store');
    Route::patch('/settings/companies/{company}', [SettingsController::class, 'updateCompany'])->name('settings.companies.update');
    Route::delete('/settings/companies/{company}', [SettingsController::class, 'destroyCompany'])->name('settings.companies.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
