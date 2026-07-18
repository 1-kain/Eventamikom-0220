<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Menyaring data transaksi berdasarkan kepemilikan jika role-nya organizer
        $transactions = Transaction::with(['event', 'user'])
            ->when($user->role === 'organizer', function($query) use ($user) {
                return $query->whereHas('event', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10);

        // FIX: Diarahkan kembali ke base view admin yang benar
        return view('admin.transactions.index', compact('transactions'));
    }
}