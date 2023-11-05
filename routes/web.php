<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('user/profile', [UserController::class, 'show'])->name('profile');
Route::post('user/profile', [UserController::class, 'update'])->name('profile');

Route::group(['middleware' => ['admin']], function () {
    Route::get('admin', [AdminController::class, 'listAllUsers'])->name('admin');
    Route::put('users/{id}/enable', [AdminController::class, 'enable'])->name('users.enable');
    Route::put('users/{id}/disable', [AdminController::class, 'disable'])->name('users.disable');
});
