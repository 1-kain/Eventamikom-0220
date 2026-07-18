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
use App\Http\Controllers\TicketScannerController;
use App\Http\Controllers\CertificateController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OrganizerController as OrganizerController;
use App\Http\Controllers\UserTicketController;

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

// 🌟 HALAMAN ETALASE ORGANIZER (PUBLIK)
Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers.index');
Route::get('/organizers/{id}', [OrganizerController::class, 'show'])->name('organizers.show');

// 🌟 FIX JALUR BUYER: Dikeluarkan dari middleware auth rute, 
// karena intersep "lempar otomatis ke Google" akan ditangani langsung di dalam CheckoutController.
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// Rute pasca-checkout tetap diamankan karena user dipastikan sudah login setelah melewati tahap di atas
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/payment/{order_id}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
// 🌟 FORM REVIEW ORGANIZER (WAJIB LOGIN)
    Route::get('/organizers/{id}/review', [OrganizerController::class, 'createReview'])->name('review.create');
    Route::post('/organizers/{id}/review', [OrganizerController::class, 'storeReview'])->name('review.store');
    // 1. Jalur menuju halaman daftar tiket lunas milik user
    Route::get('/my-tickets', [UserTicketController::class, 'indexTickets'])->name('user.tickets');
    // 2. Jalur menuju halaman daftar sertifikat milik user
    Route::get('/my-certificates', [UserTicketController::class, 'indexCertificates'])->name('user.certificates');
    // 3. Jalur tambahan: Akses unduh sertifikat dari sisi user
    Route::get('/my-certificates/{id}/download', [UserTicketController::class, 'downloadCertificate'])->name('user.certificates.download');
    });

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// Gerbang Google SSO
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// ==========================================
// 2. RUTE PROSES OTENTIKASI UNIVERSAL (URL: /login)
// ==========================================
Route::group(['middleware' => 'guest'], function () {
    // 🌟 FIX AMAN: Menampilkan halaman form lobi gabungan milik lu (Email/Password + Button Google)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
});

// Pintu Keluar Utama
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 3. RUTE AREA DASHBOARD (SECURE & ROLE-BASED)
// ==========================================

// ➡️ RUANGAN KHUSUS SUPERADMIN (URL: /admin/...)
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', RoleMiddleware::class . ':superadmin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
    Route::resource('partners', PartnerController::class);

    Route::resource('organizers', App\Http\Controllers\Admin\OrganizerController::class);
    Route::resource('events', App\Http\Controllers\Admin\SuperadminEventController::class)->except(['create', 'store']);
    Route::get('/transactions', [App\Http\Controllers\Admin\SuperadminTransactionController::class, 'index'])->name('transactions.index');
});

// ➡️ RUANGAN KHUSUS ORGANIZER (URL: /organizer/...)
Route::group(['prefix' => 'organizer', 'as' => 'organizer.', 'middleware' => ['auth', RoleMiddleware::class . ':organizer']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', EventAdminController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Scanner & Sertifikat
    Route::get('/scan-tiket', [TicketScannerController::class, 'index'])->name('scan.index');
    Route::post('/scan-tiket', [TicketScannerController::class, 'check'])->name('scan.check');
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');
    Route::get('/certificate/preview/{id}', [CertificateController::class, 'preview'])->name('certificate.preview');
    Route::get('/certificate/download/{id}', [CertificateController::class, 'download'])->name('certificate.download');
});