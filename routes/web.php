<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminIndex;
use App\Http\Controllers\DosenPageController;
// use App\Http\Controllers\BlogController;
use App\Http\Controllers\FeedbackQuestionController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LecturerManagementController;
use App\Http\Controllers\PermissionController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('landingpage');

Route::get('/dashboard', function () {
    return redirect()->route('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// admin pages
Route::middleware(['auth', 'verified', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminIndex::class)->name('index');

    // roles & permissions
    Route::resource('/permissions', PermissionController::class)->except(['show']);
    Route::resource('/roles', RoleController::class)->except(['show']);

    // users
    Route::resource('/users', UserController::class)->except(['show']);

    // blogs | comment this route below to disable Blog features
    // Route::resource('/blogs', BlogController::class);

    // KPI
    Route::resource('/kpi', KpiController::class)->except(['show']);

    // subjects / mata kuliah
    Route::resource('/subjects', SubjectController::class)->except(['show']);

    // Feedback questions
    Route::resource('/questions', FeedbackQuestionController::class)->except(['show']);

    // lecturer management
    Route::resource('/lecturer-managements', LecturerManagementController::class)->except(['show']);

    // bulk delete
    Route::delete('/bulk-delete/permissions', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::delete('/bulk-delete/roles', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::delete('/bulk-delete/users', [UserController::class, 'massDestroy'])->name('users.massDestroy');
    Route::delete('/bulk-delete/kpi', [KpiController::class, 'massDestroy'])->name('kpi.massDestroy');
    Route::delete('/bulk-delete/subjects', [SubjectController::class, 'massDestroy'])->name('subjects.massDestroy');
    Route::delete('/bulk-delete/questions', [FeedbackQuestionController::class, 'massDestroy'])->name('questions.massDestroy');
    Route::delete('/bulk-delete/lecturer-managements', [LecturerManagementController::class, 'massDestroy'])->name('lecturer-managements.massDestroy');
    // Route::delete('/bulk-delete/blogs', [BlogController::class, 'massDestroy'])->name('blogs.massDestroy');
});

// account re-verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('account/verify-new-email/{token}', [AccountController::class, 'verifyNewEmail'])->name('account.verifyNewEmail');
    Route::resource('account', AccountController::class)->only(['index', 'edit', 'update']);
});

// authenticated dosen
Route::middleware(['auth', 'verified', 'role:dosen|tendik|staff'])->group(function () {
    Route::get('/d/profile', [DosenPageController::class, 'profile'])->name('dosen.profile');
    Route::get('/d/profile/{subject_id}', [DosenPageController::class, 'subject'])->name('dosen.subject');
    Route::post('/d/presence', [DosenPageController::class, 'presence'])->name('dosen.presence');
});

// authenticated employee
Route::middleware(['auth', 'verified', 'role:dosen|tendik|staff'])->group(function () {
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
});

require __DIR__ . '/auth.php';
