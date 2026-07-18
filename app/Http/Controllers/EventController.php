<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review; // 🌟 WAJIB DI-IMPORT
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // 🌟 HITUNG RATA-RATA RATING: Tarik semua ulasan milik EO pembuat event ini
        $organizerRating = Review::where('organizer_id', $event->user_id)->avg('rating');
        
        // Format angka desimal agar rapi (misal: 4.5). Jika belum ada review, set nilainya jadi null
        $organizerRating = $organizerRating ? number_format($organizerRating, 1) : null;

        // Kirim variabel $organizerRating ke view detail event
        return view('event-detail', compact('event', 'organizerRating'));    
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
    {
        return view('ticket');
    }

    public function indexAdmin()
    {
        return view('admin.events');
    }
}