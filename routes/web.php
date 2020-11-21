<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\RowController;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome')->name('welcome');

Route::resource('files', FileController::class)->only(['index', 'create', 'store']);
Route::resource('rows', RowController::class)->only(['index']);
