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

// Handle intro screen
Route::get('/', 'GamesController@intro');

// Handle intro screen
Route::get('/intro', 'GamesController@intro');

// Handle new game
Route::get('/play', 'GamesController@play');

// Handle in game button presses
Route::post('/play', 'GamesController@playturn');

// Handle the exit page
Route::get('/exit', 'GamesController@exit');
