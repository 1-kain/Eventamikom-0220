<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $completedStatuses = ['success', 'settlement', 'capture', 'paid'];

        // 1. Inisialisasi Query Dasar
        $revenueQuery = Transaction::whereIn(DB::raw('LOWER(status)'), $completedStatuses);
        $ticketQuery = Transaction::whereIn(DB::raw('LOWER(status)'), $completedStatuses);
        $pendingQuery = Transaction::whereRaw('LOWER(status) = ?', ['pending']);
        $recentQuery = Transaction::with('event')->latest();
        $eventQuery = Event::where('date', '>=', now());

        // 🌟 ISOLASI DATA DENGAN KONDISI ROLE
        if ($user->role === 'organizer') {
            $revenueQuery->whereHas('event', function($q) use ($user) { $q->where('user_id', $user->id); });
            $ticketQuery->whereHas('event', function($q) use ($user) { $q->where('user_id', $user->id); });
            $pendingQuery->whereHas('event', function($q) use ($user) { $q->where('user_id', $user->id); });
            $recentQuery->whereHas('event', function($q) use ($user) { $q->where('user_id', $user->id); });
            $eventQuery->where('user_id', $user->id);
        }

        // 2. Eksekusi Perhitungan Hasil Filter
        $totalRevenue = $revenueQuery->sum('total_price');
        $ticketsSold  = $ticketQuery->count();
        $activeEvents = $eventQuery->count();
        $pendingOrders = $pendingQuery->count();
        $recentTransactions = $recentQuery->take(5)->get();

        // Catatan: Karena sidebar kamu di layouts/admin.blade.php sudah dinamis, 
        // kamu bisa tetap memakai satu file view 'admin.dashboard' dengan aman.
        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}