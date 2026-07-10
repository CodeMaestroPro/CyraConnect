<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Portal\CorporateDashboardController;
use App\Http\Controllers\Portal\HubDashboardController;
use App\Http\Controllers\Portal\InvestorDashboardController;
use App\Http\Controllers\Portal\StartupDashboardController;
use App\Http\Controllers\Portal\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/onboarding/role', [OnboardingController::class, 'roleSelect'])->name('onboarding.role');
    Route::post('/onboarding/role', [OnboardingController::class, 'storeRole'])->name('onboarding.role.store');
    Route::get('/onboarding/profile', [OnboardingController::class, 'profileComplete'])->name('onboarding.profile');
    Route::post('/onboarding/profile', [OnboardingController::class, 'storeProfile'])->name('onboarding.profile.store');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('student')->middleware('role:student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('startup')->middleware('role:startup_founder')->name('startup.')->group(function () {
        Route::get('/dashboard', [StartupDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('investor')->middleware('role:investor')->name('investor.')->group(function () {
        Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('hub')->middleware('role:tech_hub')->name('hub.')->group(function () {
        Route::get('/dashboard', [HubDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('corporate')->middleware('role:corporate')->name('corporate.')->group(function () {
        Route::get('/dashboard', [CorporateDashboardController::class, 'index'])->name('dashboard');
    });
});

Route::prefix('admin')->middleware(['auth', 'verified', 'active', 'role:administrator,super_administrator,moderator,support_team'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
});
