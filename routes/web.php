<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserManagmentController;
use App\Http\Controllers\first_part\TestMethodController;

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
    return view('auth.login-page');
})->name('login-page');

// Route::get('/', [AuthController::class, 'loginPage'])->name('login-page');
// // Translation

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');
Route::group(['prefix' => 'test_method'], function () {
    Route::get('/test_method', [TestMethodController::class, 'index'])->name('test_method.index');
    // Route::post('/test_method', [ TestMethodController::class, 'index'])->name('test_method.index');
});


// User Managment
Route::group(['prefix' => 'user_management'], function () {

    Route::get('/', [UserManagmentController::class, 'index'])->name('user_managment');
    Route::get('/create', [UserManagmentController::class , 'create'])->name('user_managment.create');
    Route::post('/create', [UserManagmentController::class , 'store'])->name('user_managment.store');
    Route::get('/edit/{id}' , [UserManagmentController::class , 'edit'])->name('user_managment.edit');
    Route::patch('/update/{id}' , [UserManagmentController::class , 'update'])->name('user_managment.update');
    Route::get('/delete/{id}', [UserManagmentController::class ,'destroy'])->name('user_managment.delete');

    Route::get('/signature', [UserManagmentController::class, 'signature'])->name('user_management.signature');

});


// Roles
Route::group(['prefix' => 'admin/roles'], function () {
    Route::get('/', [RoleController::class, 'index'])->name('roles');
    Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/{id}/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/delete', [RoleController::class, 'destroy'])->name('roles.delete');
});