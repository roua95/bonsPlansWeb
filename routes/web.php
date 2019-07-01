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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/register', function () {
    return view('registration.create');
});

Route::get('/category/create', function () {
    return view('categories.create');
});

Route::get('/category/delete', function () {
    return view('categories.delete');
});
Route::get('/category/index', function () {
    return view('categories.index');
});