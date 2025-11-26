<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use App\Http\Controllers\Backend\Admin\AdminController;
use App\Http\Controllers\Backend\Admin\DocumentController;
use App\Http\Controllers\Backend\Admin\PlanController;
use App\Http\Controllers\Backend\Admin\TemplateController;
use App\Http\Controllers\Backend\Client\UserController;


Route::get('/', function () {
    return view('welcome');
});





// User routes
Route::prefix('user')->middleware(['auth', IsUser::class])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard');
    Route::get('/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/user/profile', [UserController::class, 'userProfile'])->name('user.profile');
    Route::post('/user/profile/update', [UserController::class, 'userProfileUpdate'])->name('user.profile.update');
    Route::get('/user/change/password', [UserController::class, 'userChangePassword'])->name('user.change.password');
    Route::post('/user/password/update', [UserController::class, 'userPasswordUpdate'])->name('user.password.update');


});



// Admin routes
Route::prefix('admin')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard');


    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminProfileUpdate'])->name('admin.profile.update');
    Route::get('/admin/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.change.password');
    Route::post('/admin/password/update', [AdminController::class, 'adminPasswordUpdate'])->name('admin.password.update');


    // Route::controller(PlanController::class)->group(function(){

    // });
    
    Route::resource('plans', PlanController::class);
    Route::resource('templates', TemplateController::class);
    Route::post('/content/generate/{id}', [TemplateController::class, 'adminContentGenerate'])->name('content.generate');
    Route::get('/all/document', [DocumentController::class, 'adminDocument'])->name('admin.document');
    Route::get('/edit/document/{id}', [DocumentController::class, 'editAdminDocument'])->name('edit.admin.document');
    Route::post('/update/document/{id}', [DocumentController::class, 'updateAdminDocument'])->name('admin.update.document');
    Route::delete('/delete/document/{id}', [DocumentController::class, 'deleteAdminDocument'])->name('delete.admin.document');


    Route::get('/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
