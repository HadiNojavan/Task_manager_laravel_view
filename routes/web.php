<?php

use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskWebController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login.page');
Route::post('/login', [AuthWebController::class, 'login'])->name('login.web.store');
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout.web');

Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.page');
Route::get('/tasks/create', [TaskWebController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}', [TaskWebController::class, 'edit'])->name('tasks.edit');
Route::patch('/tasks/{task}', [TaskWebController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.page');
Route::get('/admin', [AdminWebController::class, 'index'])->name('admin.page');
