<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', function () {
    return '<h1>Ini adalah halaman tentang aplikasi event hub</h1>';
});

Route::get('/kontak', function () {
    return view('contact');
});
