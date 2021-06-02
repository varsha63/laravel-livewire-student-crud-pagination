<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Students;
use App\Http\Livewire\Users;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Route::get('users', Users::class);
//Route::view('users', 'livewire.hom');
//Route::view('/', 'livewire.home')->name('registration-login');
//Route::get('studens', Students::class);
Route::view('students', 'students.student');
//Route::post('login', 'Students::class');
//Route::view('register', 'Students::class');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
