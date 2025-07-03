<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\UserNotificationsController;
// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();

// User dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Proposal export for users
Route::get('/proposals/export', [ProposalController::class, 'export'])->name('proposals.export');

Route::get('/notifications', [UserNotificationsController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{notification}/mark-as-read', [UserNotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::post('/notifications/mark-all-as-read', [UserNotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

// User proposal routes (auth only)
Route::middleware('auth')->group(function () {
    Route::resource('proposals', ProposalController::class);
    Route::post('/proposals/{proposal}/request-review', [ProposalController::class, 'requestReview'])->name('proposals.request-review');
    Route::post('/proposals/{proposal}/submit-revisions', [ProposalController::class, 'submitRevisions'])->name('proposals.submit-revisions');
    
    // Client profile for users
    Route::get('/client-profile', [App\Http\Controllers\ClientProfileController::class, 'index'])->name('client.profile');
});


// ✅ All Admin Routes (protected by `auth` and `admin` middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Proposal Review
    Route::get('/proposals', [AdminController::class, 'index'])->name('proposals.index');
    Route::match(['get', 'post'], '/proposals/{id}/review', [AdminController::class, 'review'])->name('proposals.review');
   
    Route::get('/project-categories', [ProjectCategoryController::class, 'index'])->name('project-categories.index');
    Route::get('/project-categories/create', [ProjectCategoryController::class, 'create'])->name('project-categories.create');
    Route::post('/project-categories', [ProjectCategoryController::class, 'store'])->name('project-categories.store');
    Route::get('/project-categories/{id}/edit', [ProjectCategoryController::class, 'edit'])->name('project-categories.edit');
    Route::put('/project-categories/{id}', [ProjectCategoryController::class, 'update'])->name('project-categories.update');
    Route::delete('/project-categories/{id}', [ProjectCategoryController::class, 'destroy'])->name('project-categories.destroy');

    // Templates Routes
    Route::resource('templates', TemplateController::class);
    Route::get('templates/{template}/download', [TemplateController::class, 'download'])->name('templates.download');
    
    // Tasks Routes
    Route::resource('tasks', TaskController::class);

    // Settings Page
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('makeAdmin');
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('createUser');
    
    // Client Management
    Route::resource('clients', ClientController::class);
    
    // Document Management
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Notification Management
    Route::resource('notifications', NotificationController::class);
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

     Route::get('/reports/summary', [ReportController::class, 'summary'])
         ->name('reports.summary');
});
Route::post('/admin/proposals/{proposal}/feedback', [AdminController::class, 'submitFeedback'])->name('admin.proposals.feedback');
Route::post('/admin/proposals/{id}/feedback', [App\Http\Controllers\Admin\ProposalFeedbackController::class, 'store'])->name('admin.proposals.feedback');

Route::post('/admin/proposals/{proposal}/versions', [AdminController::class, 'saveProposalVersion'])->name('admin.proposals.versions');