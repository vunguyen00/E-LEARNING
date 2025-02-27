<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//cho mentormentor
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/mentor/create', [App\Http\Controllers\HomeController::class, 'createCourse'])->name('mentor.create');
Route::post('/mentor/courses', [App\Http\Controllers\HomeController::class, 'store'])->name('mentor.store');

Route::get('/mentor/{course}/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('mentor.edit');
Route::put('/mentor/{course}', [App\Http\Controllers\HomeController::class, 'update'])->name('mentor.update');
Route::delete('/mentor/{course}', [App\Http\Controllers\HomeController::class, 'destroy'])->name('mentor.destroy');
//
Route::get('/course/{course}/modules', [App\Http\Controllers\ModulesController::class, 'show'])->name('mentor.show');
Route::get('/course/{course}/modules/create', [App\Http\Controllers\ModulesController::class, 'create'])->name('module.create');
Route::post('/{course}/modules/create', [App\Http\Controllers\ModulesController::class, 'store'])->name('module.store');
Route::get('/course/{course}/module/{module}/edit', [App\Http\Controllers\ModulesController::class, 'edit'])
    ->where(['course' => '[0-9]+', 'module' => '[0-9]+'])
    ->middleware('auth')
    ->name('module.edit');
Route::put('/modules/{module}', [App\Http\Controllers\ModulesController::class, 'update'])->name('module.update');
Route::delete('/course/{course}/module/{module}/delete',[App\Http\Controllers\ModulesController::class,'destroy'])->name('module.destroy');

// cho hoc sinh

Route::get('/courses', [StudentController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [StudentController::class, 'show'])->name('courses.show');
Route::post('/courses/{id}/register', [StudentController::class, 'register'])->name('courses.register');
Route::get('/courses/{id}/learn', [StudentController::class, 'learn'])->name('courses.learn');
Route::get('/courses/{courseId}/completed-modules', [StudentController::class, 'getCompletedModules'])->name('modules.complete');
Route::post('/modules/{module}/complete', [StudentController::class, 'complete'])->name('chap.complete');
Route::get('/modules/{module}', [StudentController::class, 'viewModule'])->name('modules.show');
Route::get('/courses/{course}/modules/{module}', [StudentController::class, 'viewModule'])->name('courses.module');
Route::get('/courses/{id}/payment', [StudentController::class, 'showPaymentForm'])->name('courses.payment');
Route::post('/courses/{id}/payment', [StudentController::class, 'processPayment'])->name('courses.processPayment');
//message
Route::middleware(['auth'])->group(function () {
    Route::get('/messages/{userId?}', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages', [MessageController::class, 'store']);
});
Route::get('/messages/{receiverId}/chat', [MessageController::class, 'fetchMessages']);

// test and review course
Route::get('/courses/{course}/test/{test}', [TestController::class, 'show'])->name('courses.test');
Route::get('/courses/{course}/review', [CourseController::class, 'review'])->name('courses.review');
Route::post('/courses/{course}/review', [CourseController::class, 'submitReview'])->name('courses.review.submit');

Route::get('/courses/{course}/tests/create', [TestController::class, 'create'])->name('test.create');
Route::post('/courses/{course}/tests', [TestController::class, 'store'])->name('tests.store');
Route::get('/tests/{test}', [TestController::class, 'show'])->name('courses.test.show');

Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
Route::post('/courses/{course}/test/{test}/submit', [TestController::class, 'submit'])
    ->name('courses.test.submit');
