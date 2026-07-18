<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SuperadminTransactionController extends Controller
{
    public function index(Request $request)
    {
        // 🌟 PERBAIKAN: 
        // 1. Muat 'event.user' (Vendor) DAN 'user' (Pembeli/Customer)
        // 2. Ubah get() menjadi paginate() demi keamanan performa database
        $transactions = Transaction::with(['event.user', 'user'])
            ->latest()
            ->paginate(10); // Menampilkan 10 transaksi per halaman

        // 🌟 CATATAN: Pastikan folder view terpisah dari milik Organizer jika polanya sama dengan Event kemarin
        return view('admin.transactions.index', compact('transactions'));
    }
}