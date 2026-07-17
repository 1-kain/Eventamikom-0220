<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;

// Import Controller Baru dari Fitur Scan & Sertifikat
use App\Http\Controllers\TicketScannerController;
use App\Http\Controllers\CertificateController;

// ==========================================
// 1. RUTE AREA PUBLIK / PENGUNJUNG GUEST
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tentang', function () {
    return '<h1>Ini adalah Halaman Tentang Aplikasi Event Hub</h1>';
});
Route::get('/kontak', function () { return view('contact'); });
Route::get('/profil', function () { return view('profil'); });
Route::get('/katalog', function () { return view('katalog'); });
Route::get('/bantuan', function () { return view('bantuan'); });

Route::get('/event/{event}', [EventController::class, 'show'])->name('event.show');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::post('/payment/{order_id}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// FIX: Hanya gunakan satu rute webhook dari Pertemuan 12
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


// ==========================================
// 2. RUTE AREA ADMIN & PANITIA (SECURE)
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    
    // Autentikasi Admin
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Tembok Keamanan: Hanya user yang sudah login sebagai admin/panitia yang bisa masuk
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
        Route::resource('partners', PartnerController::class);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // INTEGRASI: Fitur Aplikasi Penjaga Pintu (Check-in Scanner)
        Route::get('/scan-tiket', [TicketScannerController::class, 'index'])->name('scan.index');
        Route::post('/scan-tiket', [TicketScannerController::class, 'check'])->name('scan.check');

        // INTEGRASI: Fitur Penerbitan E-Certificate Kehadiran Otomatis
        Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');
        Route::get('/certificate/preview/{id}', [CertificateController::class, 'preview'])->name('certificate.preview');
        Route::get('/certificate/download/{id}', [CertificateController::class, 'download'])->name('certificate.download');
    });
});