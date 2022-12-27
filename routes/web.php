<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/preview-sj/{id?}', [App\Http\Controllers\HomeController::class, 'preview_sj'])->name('preview-sj');
Route::get('/preview-slip/{id?}', [App\Http\Controllers\HomeController::class, 'preview_slip'])->name('preview-slip');
Route::get('/preview-coa/{id?}', [App\Http\Controllers\HomeController::class, 'preview_coa'])->name('preview-coa');
Route::get('/test1234', [App\Http\Controllers\HomeController::class, 'test1234'])->name('test1234');
Route::post('/login-custome', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/qc/download-excel/{bln?}/{category?}', [
    'as' => 'qc-download-excel',
    'uses' => 'HomeController@qc_download_excel'
]);
Route::get('home', function () {
    return redirect('/dashboard');
});

Route::get('/{vue_capture?}', function () {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
