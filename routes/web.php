<?php

use App\Exports\SampleCourses;
use App\Exports\SampleEmployees;
use App\Exports\SampleStudents;
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
use App\Http\Controllers\TendikPositionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    if ($user->hasRole(['dosen', 'tendik'])) {
        return redirect()->route('employees.welcome');
    }
    if ($user->hasRole(['admin', 'super admin'])) {
        return redirect()->route('admin.index');
    }
    return redirect()->route('student.index');
})->name('landingpage');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    if ($user->hasRole(['dosen', 'tendik'])) {
        return redirect()->route('employees.welcome');
    }
    if ($user->hasRole(['admin', 'super admin'])) {
        return redirect()->route('admin.index');
    }
    return redirect()->route('student.index');
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
    Route::get('/leaderboard/kpi/{kpi_id}', [KpiController::class, 'leaderboard'])->name('kpi.leaderboard');
    Route::get('/leaderboard/kpi/{kpi_id}/detail', [KpiController::class, 'leaderboard_detail'])->name('kpi.leaderboard.detail');
    Route::resource('/kpi', KpiController::class)->except(['show']);

    // presence scopes
    Route::resource('/presence-scopes', SubjectController::class)->except(['show']);

    // semesters
    // Route::resource('/semesters', SemesterController::class)->except(['show']);

    // majors / jurusan
    Route::resource('/majors', MajorController::class)->except(['show']);

    // courses / mata kuliah
    Route::get('/courses/sample', function(){
        return Excel::download(new SampleCourses, 'courses.xlsx');
    })->name('download-sample-courses');
    Route::resource('/courses', CourseController::class)->except(['show']);

    // Feedback questions
    Route::resource('/questions', FeedbackQuestionController::class)->except(['show']);

    // Feedbacks
    Route::resource('/feedbacks', FeedbackController::class)->except(['show', 'edit', 'update', 'create']);

    // lecturer management
    Route::resource('/employees-presence-quota', LecturerManagementController::class)->except(['show']);

    // employee
    Route::get('/employees/sample', function(){
        return Excel::download(new SampleEmployees, 'employees.xlsx');
    })->name('download-sample-employees');
    Route::resource('/employees', EmployeeController::class)->except(['show']);

    // mahasiswa
    Route::get('/students/sample', function(){
        return Excel::download(new SampleStudents, 'students.xlsx');
    })->name('download-sample-students');
    Route::resource('/students', StudentController::class)->except(['show']);

    // achievement
    Route::post('/achievements/generate/{kpi_id}', [AchievementController::class, 'generate'])->name('achievements.generate');
    Route::resource('/achievements', AchievementController::class)->only(['index', 'destroy']);

    // general settings
    Route::resource('/settings', SettingController::class)->only(['index', 'update']);

    // tendik positions
    Route::resource('/tendik-positions', TendikPositionController::class)->except(['show']);

    // bulk delete
    Route::delete('/bulk-delete/permissions', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::delete('/bulk-delete/roles', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::delete('/bulk-delete/users', [UserController::class, 'massDestroy'])->name('users.massDestroy');
    Route::delete('/bulk-delete/employees', [EmployeeController::class, 'massDestroy'])->name('employees.massDestroy');
    Route::delete('/bulk-delete/students', [StudentController::class, 'massDestroy'])->name('students.massDestroy');
    Route::delete('/bulk-delete/kpi', [KpiController::class, 'massDestroy'])->name('kpi.massDestroy');
    Route::delete('/bulk-delete/presence-scopes', [SubjectController::class, 'massDestroy'])->name('presence-scopes.massDestroy');
    Route::delete('/bulk-delete/questions', [FeedbackQuestionController::class, 'massDestroy'])->name('questions.massDestroy');
    Route::delete('/bulk-delete/employees-presence-quota', [LecturerManagementController::class, 'massDestroy'])->name('employees-presence-quota.massDestroy');
    // Route::delete('/bulk-delete/semesters', [SemesterController::class, 'massDestroy'])->name('semesters.massDestroy');
    Route::delete('/bulk-delete/majors', [MajorController::class, 'massDestroy'])->name('majors.massDestroy');
    Route::delete('/bulk-delete/courses', [CourseController::class, 'massDestroy'])->name('courses.massDestroy');
    Route::delete('/bulk-delete/achievements', [AchievementController::class, 'massDestroy'])->name('achievements.massDestroy');
    Route::delete('/bulk-delete/feedbacks', [FeedbackController::class, 'massDestroy'])->name('feedbacks.massDestroy');
    Route::delete('/bulk-delete/tendik-positions', [TendikPositionController::class, 'massDestroy'])->name('tendik-positions.massDestroy');
    // Route::delete('/bulk-delete/blogs', [BlogController::class, 'massDestroy'])->name('blogs.massDestroy');
});

// account re-verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('account/verify-new-email/{token}', [AccountController::class, 'verifyNewEmail'])->name('account.verifyNewEmail');
    Route::resource('account', AccountController::class)->only(['index', 'edit', 'update']);
});

// authenticated employees
Route::middleware(['auth', 'verified', 'role:dosen|tendik|staff', 'user_active'])->group(function () {
    Route::get('/employees', [DosenPageController::class, 'welcome'])->name('employees.welcome');
    Route::get('/employees/leaderboard', [LeaderboardController::class, 'index'])->name('employees.leaderboard.index');
    Route::get('/employees/profile', [DosenPageController::class, 'profile'])->name('employees.profile');
    Route::get('/employees/profile/{id}', [DosenPageController::class, 'profile_show'])->name('employees.profile.show');
    Route::post('/employees/presence', [DosenPageController::class, 'presence'])->name('employees.presence.store');
    Route::get('/employees/presence', [DosenPageController::class, 'presence_index'])->name('employees.presence.index');
    Route::get('/employees/presence/{subject_id}', [DosenPageController::class, 'subject'])->name('employees.presence.show');
});

// authenticated students
Route::middleware(['auth', 'verified', 'role:mahasiswa', 'user_active'])->group(function () {
    Route::get('/students', [StudentFeedbackController::class, 'index'])->name('student.index');
    Route::get('/students/profile', [StudentFeedbackController::class, 'profile'])->name('student.profile');
    Route::get('/students/feedback', [StudentFeedbackController::class, 'feedbacks'])->name('student.courses.index');
    Route::get('/students/feedback/{course_id}/edu', [StudentFeedbackController::class, 'feedback'])->name('student.courses.feedback');
    Route::post('/students/feedback/{course_id}/edu', [StudentFeedbackController::class, 'store'])->name('student.store');
    Route::get('/students/feedback/{tendik_position_id}/nonedu', [StudentFeedbackController::class, 'nonedu_feedback'])->name('student.courses.feedback.nonedu');
    Route::post('/students/feedback/{tendik_position_id}/nonedu', [StudentFeedbackController::class, 'nonedu_store'])->name('student.store.nonedu');
});

Route::middleware(['auth', 'verified', 'role:mahasiswa|tendik|staff|dosen'])->group(function () {
    Route::get('/settings', function() {
        return view('settings.index');
    })->name('settings.index');
    Route::patch('/settings/update', [AccountController::class, 'userUpdate'])->name('settings.update');
});

require __DIR__ . '/auth.php';
