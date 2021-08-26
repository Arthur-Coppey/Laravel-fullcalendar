<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('fullcalendar')->name('fullcalendar.')->group(function () {
    Route::get('/', 'FullCalendarController@index')->name('index');
    Route::post('/create', 'FullCalendarController@create')->name('create');
    Route::post('/update', 'FullCalendarController@update')->name('update');
    Route::post('/delete', 'FullCalendarController@delete')->name('delete');
});
