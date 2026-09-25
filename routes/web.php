<?php

use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\HomeWebController;
use App\Http\Controllers\TaskWebController;
use App\Http\Controllers\UserWebController;
use Illuminate\Support\Facades\Route;


Route::get('/tasks/assign', [TaskWebController::class, 'assignPage'])
    ->name('tasks.assign.page');

Route::post('/tasks/assign', [TaskWebController::class, 'assign'])
    ->name('tasks.assign.web.store');

Route::get('/tasks/unassign', [TaskWebController::class, 'unassignPage'])
    ->name('tasks.unassign.page');

Route::delete('/tasks/{task}/unassign/{user}', [TaskWebController::class, 'unassign'])
    ->name('tasks.unassign.web');


Route::get('/', [HomeWebController::class, 'index'])->name('home.page');

Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login.page');
Route::post('/login', [AuthWebController::class, 'login'])->name('login.web.store');

Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register.page');
Route::post('/register', [AuthWebController::class, 'register'])->name('register.web.store');

Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout.web');

Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.page');
Route::get('/tasks/create', [TaskWebController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskWebController::class, 'edit'])->name('tasks.edit');
Route::patch('/tasks/{task}', [TaskWebController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');

//admin
Route::get('/admin', [AdminWebController::class, 'index'])->name('admin.page');
Route::get('/users', [UserWebController::class, 'index'])->name('users.page');
Route::delete('/users/{user}', [UserWebController::class, 'destroy'])->name('users.destroy');
Route::get('/tasks-trashed', [TaskWebController::class, 'trashed'])->name('tasks.trashed.page');
Route::patch('/tasks/{task}/restore', [TaskWebController::class, 'restore'])->name('tasks.restore.web');
Route::get('/admins/create', [AdminWebController::class, 'create'])->name('admins.create.page');
Route::post('/admins', [AdminWebController::class, 'store'])->name('admins.store');

Route::delete('/tasks/{id}/force-delete', [TaskWebController::class, 'forceDelete'])->name('tasks.force-delete.web');
