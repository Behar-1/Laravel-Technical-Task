<?php

use App\Http\Controllers\IssuesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::resource('issues', IssuesController::class);
    Route::resource('tags', TagController::class);

    Route::post('issues/{issue}/tags/attach', [IssuesController::class, 'attachTag'])->name('issues.tags.attach');
    Route::post('issues/{issue}/tags/detach', [IssuesController::class, 'detachTag'])->name('issues.tags.detach');
    Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
    Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');
    Route::post('/issues/{issue}/users/attach', [IssuesController::class, 'attachUser'])->name('issues.users.attach');
    Route::post('/issues/{issue}/users/detach', [IssuesController::class, 'detachUser'])->name('issues.users.detach');
});

require __DIR__.'/auth.php';
