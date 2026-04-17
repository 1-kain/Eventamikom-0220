<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', function () {
    return '<h1>Ini adalah Halaman Tentang Aplikasi Event Hub</h1>';
});

Route::get('/kontak', function () {
    return view('contact');
});

Route::get('/profil', function () {
    return view('profil');
});

Route::get('/katalog', function () {
    return view('katalog');
});

Route::get('/bantuan', function () {
    return view('bantuan');
});

//Rute User Area
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/event/1', [EventController::class, 'show'])->name('event.show');

Route::get('/checkout',[EventController::class, 'checkout'])->name('checkout');

Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

//Rute Admin Area
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/events', [EventController::class, 'indexAdmin'])->name('events.index');
    Route::get('/transactions',[TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    });