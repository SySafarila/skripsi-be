<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdminIndex;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DosenPageController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackQuestionController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LecturerManagementController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeedbackController;
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

    // presence scopes
    Route::resource('/presence-scopes', SubjectController::class)->except(['show']);

    // semesters
    // Route::resource('/semesters', SemesterController::class)->except(['show']);

    // majors / jurusan
    Route::resource('/majors', MajorController::class)->except(['show']);

    // courses / mata kuliah
    Route::resource('/courses', CourseController::class)->except(['show']);

    // Feedback questions
    Route::resource('/questions', FeedbackQuestionController::class)->except(['show']);

    // Feedbacks
    Route::resource('/feedbacks', FeedbackController::class)->except(['show', 'edit', 'create']);

    // lecturer management
    Route::resource('/employees-management', LecturerManagementController::class)->except(['show']);

    // employee
    Route::resource('/employees', EmployeeController::class)->except(['show']);

    // mahasiswa
    Route::resource('/students', StudentController::class)->except(['show']);

    // achievement
    Route::post('/achievements/generate/{kpi_id}', [AchievementController::class, 'generate'])->name('achievements.generate');
    Route::resource('/achievements', AchievementController::class)->except(['show']);

    // general settings
    Route::resource('/settings', SettingController::class)->only(['index', 'update']);

    // bulk delete
    Route::delete('/bulk-delete/permissions', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::delete('/bulk-delete/roles', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::delete('/bulk-delete/users', [UserController::class, 'massDestroy'])->name('users.massDestroy');
    Route::delete('/bulk-delete/employees', [EmployeeController::class, 'massDestroy'])->name('employees.massDestroy');
    Route::delete('/bulk-delete/students', [StudentController::class, 'massDestroy'])->name('students.massDestroy');
    Route::delete('/bulk-delete/kpi', [KpiController::class, 'massDestroy'])->name('kpi.massDestroy');
    Route::delete('/bulk-delete/presence-scopes', [SubjectController::class, 'massDestroy'])->name('presence-scopes.massDestroy');
    Route::delete('/bulk-delete/questions', [FeedbackQuestionController::class, 'massDestroy'])->name('questions.massDestroy');
    Route::delete('/bulk-delete/employees-management', [LecturerManagementController::class, 'massDestroy'])->name('employees-management.massDestroy');
    // Route::delete('/bulk-delete/semesters', [SemesterController::class, 'massDestroy'])->name('semesters.massDestroy');
    Route::delete('/bulk-delete/majors', [MajorController::class, 'massDestroy'])->name('majors.massDestroy');
    Route::delete('/bulk-delete/courses', [CourseController::class, 'massDestroy'])->name('courses.massDestroy');
    Route::delete('/bulk-delete/achievements', [AchievementController::class, 'massDestroy'])->name('achievements.massDestroy');
    Route::delete('/bulk-delete/feedbacks', [FeedbackController::class, 'massDestroy'])->name('feedbacks.massDestroy');
    // Route::delete('/bulk-delete/blogs', [BlogController::class, 'massDestroy'])->name('blogs.massDestroy');
});

// account re-verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('account/verify-new-email/{token}', [AccountController::class, 'verifyNewEmail'])->name('account.verifyNewEmail');
    Route::resource('account', AccountController::class)->only(['index', 'edit', 'update']);
});

// authenticated dosen
Route::middleware(['auth', 'verified', 'role:dosen|tendik|staff'])->group(function () {
});

// authenticated employees
Route::middleware(['auth', 'verified', 'role:dosen|tendik|staff'])->group(function () {
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/profile', [DosenPageController::class, 'profile'])->name('profile');
    Route::get('/profile/presence/{subject_id}', [DosenPageController::class, 'subject'])->name('presence.show');
    Route::post('/presence', [DosenPageController::class, 'presence'])->name('presence.store');
});

// authenticated students
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    Route::get('/student', [StudentFeedbackController::class, 'index'])->name('student.index');
    Route::get('/student/courses', [StudentFeedbackController::class, 'courses'])->name('student.courses');
    Route::get('/student/courses/{course_id}/feedback', [StudentFeedbackController::class, 'feedback'])->name('student.feedback');
    Route::post('/student/courses/{course_id}/feedback', [StudentFeedbackController::class, 'store'])->name('student.store');
});

require __DIR__ . '/auth.php';
