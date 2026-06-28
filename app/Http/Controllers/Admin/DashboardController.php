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
        $completedStatuses = ['success', 'settlement', 'capture', 'paid'];

        $totalRevenue = Transaction::whereIn(DB::raw('LOWER(status)'), $completedStatuses)
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn(DB::raw('LOWER(status)'), $completedStatuses)
            ->count();

        $activeEvents = Event::count();
        $pendingOrders = Transaction::whereRaw('LOWER(status) = ?', ['pending'])->count();
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}
