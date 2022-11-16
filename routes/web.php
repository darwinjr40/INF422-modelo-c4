<?php

use App\Http\Controllers\HomeController;
use App\Http\Livewire\Home;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return redirect(route('login'));
});

Auth::routes();
//componente de livewire directamente
// Route::get('/home', Home::class)->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home2', [HomeController::class, 'index2'])->name('home2');