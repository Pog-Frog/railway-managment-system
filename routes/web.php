<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController; ## Admin Controller
use App\Http\Controllers\UserController; ## Users Controller
use App\Http\Controllers\EmployeeController; ##employee Controller

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
##ADMIN
Route::get("admin/register_admin", [CustomAuthController::class, 'admin_register_index'])->name('admin_register_index');
Route::post("admin/register", [CustomAuthController::class, 'admin_register'])->name('register_admin');
Route::get("admin/", [CustomAuthController::class, 'admin_login_index'])->name('admin_login_index')->middleware('alreadyloggedin');
Route::post("admin/login", [CustomAuthController::class, 'admin_login'])->name('login_admin')->middleware('alreadyloggedin');
Route::get("admin/dashboard", [CustomAuthController::class, 'admin_index'])->middleware('isloggedin');
Route::get("admin/logout", [CustomAuthController::class, 'admin_logout'])->name('logout')->middleware('isloggedin');
Route::get("admin/trains", [CustomAuthController::class, 'trains_index'])->name('train_management')->middleware('isloggedin');
Route::post("admin/trains/insert_train", [CustomAuthController::class, 'insert_train'])->name('insert_train')->middleware('isloggedin');
Route::get("admin/trains/edit_train_index/{train_id}", [CustomAuthController::class, 'edit_train_index'])->name('edit_train_index')->middleware('isloggedin');
Route::post("admin/trains/edit_train/{train_id}", [CustomAuthController::class, 'edit_train'])->name('edit_train')->middleware('isloggedin');
Route::post("admin/trains/edit_train/delete_train/{train_id}", [CustomAuthController::class, 'delete_train'])->name('delete_train')->middleware('isloggedin');
Route::get("admin/trains/view_trains", [CustomAuthController::class, 'view_trains'])->name('view_trains')->middleware('isloggedin');
Route::get("admin/trains/view_trains/search_trains", [CustomAuthController::class, 'search_trains'])->name('search_trains')->middleware('isloggedin');

##USER
Route::get("user/logout", [UserController::class, 'user_logout'])->name('logout')->middleware('isloggedin_user');
Route::get("user/", [UserController::class, 'user_login_index'])->name('user_login_index')->middleware('alreadyloggedin_user');
Route::post("user/login", [UserController::class, 'user_login'])->name('login_user')->middleware('alreadyloggedin_user');
Route::get("user/register_user", [UserController::class, 'user_register_index'])->name('user_register_index');
Route::post("user/register", [UserController::class, 'user_register'])->name('register_user')->middleware('alreadyloggedin_user');
Route::get("/", [UserController::class, 'user_index']);

##EMPLOYEE
Route::get("employee/", [EmployeeController::class, 'employee_login_index'])->name('employee_login_index')->middleware('alreadyloggedin_employee');
Route::post("employee/login", [EmployeeController::class, 'employee_login'])->name('login_employee')->middleware('alreadyloggedin_employee');
Route::get("employee/home", [EmployeeController::class, 'employee_index'])->middleware('isloggedin_employee');
Route::get("employee/logout", [EmployeeController::class, 'employee_logout'])->name('logout')->middleware('isloggedin_employee');

