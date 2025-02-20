<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home');
});

//Route::resource('/backend/users', UserController::class); //Deze vervangen we met code hieronder

//Route::middleware(['auth'])->group(function(){
//    Route::resource('/backend/users', UserController::class);
//});
Route::group(['prefix'=>'backend','middleware'=>'auth'],function(){
    Route::resource('/users', UserController::class);
    Route::patch('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('/blogs', BlogController::class);
});

Route::get('/backend', function () {
    return view('backend.index');
})->middleware(['auth', 'verified'])->name('backend.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
