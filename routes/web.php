<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("admin/register_admin", [CustomAuthController::class, 'admin_register_index'])->name('admin_register_index');
Route::post("admin/register", [CustomAuthController::class, 'admin_register'])->name('register_admin');
Route::get("admin/", [CustomAuthController::class, 'admin_login_index'])->name('admin_login_index')->middleware('alreadyloggedin');
Route::post("admin/login", [CustomAuthController::class, 'admin_login'])->name('login_admin')->middleware('alreadyloggedin');
Route::get("admin/dashboard", [CustomAuthController::class, 'admin_index'])->middleware('isloggedin');
Route::get("admin/logout", [CustomAuthController::class, 'admin_logout'])->name('logout');
