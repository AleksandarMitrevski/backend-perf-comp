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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('benchmarking/init', 'BenchmarkingController@init');
Route::get('benchmarking/matrix_multiplication', 'BenchmarkingController@matrixMultiplication');
Route::get('benchmarking/sieve_of_atkin', 'BenchmarkingController@sieveOfAtkin');
Route::get('benchmarking/quicksort', 'BenchmarkingController@quicksort');
