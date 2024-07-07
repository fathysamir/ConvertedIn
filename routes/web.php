<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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
    
    if(!auth()->user()){
        return redirect('/login');
    }else{
        return redirect('/home');
    }
});

Route::get('/login', [AuthController::class, 'login_view'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
    Route::get('/update_top_users', [AuthController::class, 'updateTopUsersInHome'])->name('updateTopUsersInHome');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    /////////////////////////////////////////

    /////////////////////////////////////////
    Route::any('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('add.user');
    Route::post('/users/create', [UserController::class, 'store'])->name('create.user');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edit.user');
    Route::post('/user/update/{user}', [UserController::class, 'update'])->name('update.user');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('delete.user');
    /////////////////////////////////////////
    Route::any('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('add.project');
    Route::post('/projects/create', [ProjectController::class, 'store'])->name('create.project');
    Route::get('/project/edit/{id}', [ProjectController::class, 'edit'])->name('edit.project');
    Route::post('/project/update/{project}', [ProjectController::class, 'update'])->name('update.project');
    Route::get('/project/delete/{project}', [ProjectController::class, 'delete'])->name('delete.project');
    /////////////////////////////////////////
    Route::any('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('add.task');
    Route::post('/tasks/create', [TaskController::class, 'store'])->name('create.task');
    Route::get('/task/edit/{id}', [TaskController::class, 'edit'])->name('edit.task');
    Route::post('/task/update/{task}', [TaskController::class, 'update'])->name('update.task');
    Route::get('/task/delete/{task}', [TaskController::class, 'delete'])->name('delete.task');
});
