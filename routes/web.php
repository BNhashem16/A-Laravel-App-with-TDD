<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('projects.tasks', TaskController::class)->only(['store', 'update', 'show', 'destroy']);
Route::resource('projects.invitations', InvitationController::class)->only(['store']);
Route::resource('projects', ProjectController::class);

// Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
