<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mahasiswa/' , 'AdminController@cek');
Route::get('/mahasiswa/tambah' , 'AdminController@tambah');
Route::post('/mahasiswa/tambah' , 'AdminController@tambah_data')->name('tambah-mahasiswa');
Route::get('/mahasiswa/edit/{id}' , 'AdminController@edit');
Route::put('/mahasiswa/edit/{id}', 'AdminController@edit_data');
Route::delete('/mahasiswa/hapus/{id}' , 'AdminController@hapus_data');