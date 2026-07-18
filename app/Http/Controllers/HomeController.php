<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Partner; // 🌟 JANGAN LUPA IMPORT MODEL PARTNER
use Carbon\Carbon;      // 🌟 Mengaktifkan radar waktu real-time
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // 1. FILTER EVENT AKTIF: Stok tersedia (> 0) DAN Belum Terlewat (>= $now)
        $events = Event::where('stock', '>', 0)
            ->where('date', '>=', $now)
            ->with('category') // 💡 Eager Loading: Mencegah N+1 query saat memanggil $event->category->name
            ->latest()
            ->take(6) // Batasi 6 data saja agar layout grid 3 kolom tetap simetris
            ->get();

        // 2. AMBIL DATA PARTNER: Wajib ditarik agar loop @forelse($partners) tidak pecah
        $partners = Partner::latest()->get();

        // Lempar kedua data ke view welcome
        return view('welcome', compact('events', 'partners'));
    }
}