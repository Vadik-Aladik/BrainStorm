<?php

use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\UserCheck;
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

// Route::get('/test', [App\Http\Controllers\IndexController::class, 'test'])->name('student.test');

Route::middleware([UserCheck::class])->group(function(){
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('user.login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'signin'])->name('user.signin');
    Route::get('/registration', [App\Http\Controllers\AuthController::class, 'registration'])->name('user.registration');
    Route::post('/registration', [App\Http\Controllers\AuthController::class, 'store'])->name('user.store');
});

Route::middleware([AuthCheck::class])->group(function(){
    Route::get('/', [App\Http\Controllers\IndexController::class, 'main'])->name('student.index');
    Route::get('/course/{id}', [App\Http\Controllers\IndexController::class, 'course'])->name('student.course');
    Route::get('/course/{id}/test/{idTest}', [App\Http\Controllers\IndexController::class, 'test'])->name('student.test');
    Route::post('/test/{idTest}/get', [App\Http\Controllers\IndexController::class, 'testGet'])->name('student.test.get');
    Route::post('/test', [App\Http\Controllers\IndexController::class, 'userTest']);

    Route::get('/personal', [App\Http\Controllers\AuthController::class, 'personal'])->name('student.personal');
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('student.logout');

    Route::middleware([AdminCheck::class])->group(function(){
        Route::get('/admin/new/test/course/{id}', [App\Http\Controllers\AdminController::class, 'createTest'])->name('admin.create.test');
        Route::post('/admin/new/test/course/{id}', [App\Http\Controllers\AdminController::class, 'createTestPost']);

        Route::get('/admin/new/course', [App\Http\Controllers\AdminController::class, 'createCourse'])->name('admin.create.course');
        Route::post('/admin/new/course', [App\Http\Controllers\AdminController::class, 'createCoursePost']);

        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'main'])->name('admin.main'); // первая работа с получением данных с сторны фронта, используя асинхронные запрсы Post
        Route::post('/adminData', [App\Http\Controllers\AdminController::class, 'mainPost']); // первая работа с получением данных с сторны фронта, используя асинхронные запрсы Post

        Route::get('/admin/course/{id}', [App\Http\Controllers\AdminController::class, 'course'])->name('admin.course');
    });
});


