<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationDirectoryController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StudentApplicationController;
use App\Http\Controllers\StudentBookmarkController;
use App\Http\Controllers\StudentCertificateController;
use App\Http\Controllers\StudentInternshipController;
use App\Http\Controllers\StudentPortfolioController;
use App\Http\Controllers\StudentPortfolioItemController;
use App\Http\Controllers\StudentResumeController;
use App\Http\Controllers\StartupDirectoryController;
use App\Http\Controllers\StartupMediaController;
use App\Http\Controllers\StartupMilestoneController;
use App\Http\Controllers\StartupPitchDeckController;
use App\Http\Controllers\StartupProfileController;
use App\Http\Controllers\Portal\CorporateDashboardController;
use App\Http\Controllers\Portal\HubDashboardController;
use App\Http\Controllers\Portal\InvestorDashboardController;
use App\Http\Controllers\Portal\StartupDashboardController;
use App\Http\Controllers\Portal\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/organizations', [OrganizationDirectoryController::class, 'index'])->name('organizations.index');
Route::get('/organizations/{slug}', [OrganizationDirectoryController::class, 'show'])
    ->name('organizations.show')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');

Route::get('/locations/states', [LocationController::class, 'states'])->name('locations.states');
Route::get('/locations/cities', [LocationController::class, 'cities'])->name('locations.cities');

Route::get('/startups', [StartupDirectoryController::class, 'index'])->name('startups.index');
Route::get('/startups/{slug}/pitch-deck', [StartupPitchDeckController::class, 'show'])
    ->name('startups.pitch-deck')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
Route::get('/startups/{slug}', [StartupDirectoryController::class, 'show'])
    ->name('startups.show')
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');

Route::get('/students/{user}/portfolio', [StudentPortfolioController::class, 'show'])
    ->name('students.portfolio.show');

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

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/role', [RoleProfileController::class, 'edit'])->name('role.edit');
        Route::put('/role', [RoleProfileController::class, 'update'])->name('role.update');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/account', [SettingsController::class, 'account'])->name('account');
        Route::get('/password', [SettingsController::class, 'password'])->name('password');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
    });

    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::get('/users/{user}', [ProfileController::class, 'publicShow'])->name('users.show');

    Route::prefix('my-organizations')->name('organizations.')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('mine');
        Route::get('/create', [OrganizationController::class, 'create'])->name('create');
        Route::post('/', [OrganizationController::class, 'store'])->name('store');
        Route::get('/{organization}', [OrganizationController::class, 'manage'])->name('manage');
        Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
        Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
        Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('destroy');
        Route::post('/{organization}/members', [OrganizationMemberController::class, 'store'])->name('members.store');
        Route::put('/{organization}/members/{member}', [OrganizationMemberController::class, 'update'])->name('members.update');
        Route::delete('/{organization}/members/{member}', [OrganizationMemberController::class, 'destroy'])->name('members.destroy');
    });

    Route::prefix('student')->middleware('role:student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/portfolio', [StudentPortfolioController::class, 'index'])->name('portfolio.index');
        Route::post('/portfolio/items', [StudentPortfolioItemController::class, 'store'])->name('portfolio.items.store');
        Route::put('/portfolio/items/{item}', [StudentPortfolioItemController::class, 'update'])->name('portfolio.items.update');
        Route::delete('/portfolio/items/{item}', [StudentPortfolioItemController::class, 'destroy'])->name('portfolio.items.destroy');
        Route::post('/resume', [StudentResumeController::class, 'store'])->name('resume.store');
        Route::delete('/resume', [StudentResumeController::class, 'destroy'])->name('resume.destroy');
        Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
        Route::post('/certificates', [StudentCertificateController::class, 'store'])->name('certificates.store');
        Route::delete('/certificates/{certificate}', [StudentCertificateController::class, 'destroy'])->name('certificates.destroy');
        Route::get('/applications', [StudentApplicationController::class, 'index'])->name('applications.index');
        Route::post('/applications', [StudentApplicationController::class, 'store'])->name('applications.store');
        Route::put('/applications/{application}', [StudentApplicationController::class, 'update'])->name('applications.update');
        Route::delete('/applications/{application}', [StudentApplicationController::class, 'destroy'])->name('applications.destroy');
        Route::get('/bookmarks', [StudentBookmarkController::class, 'index'])->name('bookmarks.index');
        Route::post('/bookmarks', [StudentBookmarkController::class, 'store'])->name('bookmarks.store');
        Route::delete('/bookmarks/{bookmark}', [StudentBookmarkController::class, 'destroy'])->name('bookmarks.destroy');
        Route::get('/internships', [StudentInternshipController::class, 'index'])->name('internships.index');
    });

    Route::prefix('startup')->middleware('role:startup_founder')->name('startup.')->group(function () {
        Route::get('/dashboard', [StartupDashboardController::class, 'index'])->name('dashboard');
        Route::get('/{organization}/profile', [StartupProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/{organization}/profile', [StartupProfileController::class, 'update'])->name('profile.update');
        Route::post('/{organization}/verification', [StartupProfileController::class, 'requestVerification'])->name('verification.request');
        Route::post('/{organization}/pitch-deck', [StartupPitchDeckController::class, 'store'])->name('pitch-deck.store');
        Route::delete('/{organization}/pitch-deck', [StartupPitchDeckController::class, 'destroy'])->name('pitch-deck.destroy');
        Route::post('/{organization}/milestones', [StartupMilestoneController::class, 'store'])->name('milestones.store');
        Route::put('/{organization}/milestones/{milestone}', [StartupMilestoneController::class, 'update'])->name('milestones.update');
        Route::delete('/{organization}/milestones/{milestone}', [StartupMilestoneController::class, 'destroy'])->name('milestones.destroy');
        Route::post('/{organization}/media', [StartupMediaController::class, 'store'])->name('media.store');
        Route::delete('/{organization}/media/{media}', [StartupMediaController::class, 'destroy'])->name('media.destroy');
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
    Route::get('/organizations', [AdminOrganizationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/verify', [AdminOrganizationController::class, 'verify'])->name('organizations.verify');
    Route::post('/organizations/{organization}/unverify', [AdminOrganizationController::class, 'unverify'])->name('organizations.unverify');
});
