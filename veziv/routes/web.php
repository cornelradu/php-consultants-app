<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConsultantsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::get('consultants', [IndexController::class, 'showAllConsultants']);
Route::get('registrationform', [IndexController::class, 'register']);;
Route::get('loginform', [IndexController::class, 'loginform']);;

Route::get('consultant', [ConsultantsController::class, 'showAllConsultants']);
Route::post('create_new_appointment', [ConsultantsController::class, 'createNewAppointment']);
Route::post('add_new_consultant', [ConsultantsController::class, 'addNewConsultant']);
Route::post('delete_appointment', [ConsultantsController::class, 'deleteAppointment']);
Route::post('delete_consultant', [ConsultantsController::class, 'deleteConsultant']);


