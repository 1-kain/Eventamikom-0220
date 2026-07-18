<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrganizerController extends Controller
{
    // 1. HALAMAN ETALASE UTAMA: Menampilkan daftar Organizer
    public function index()
    {
        $organizers = User::where('role', 'organizer')->latest()->get();
        return view('organizers.index', compact('organizers'));
    }

    // 2. HALAMAN DETAIL ORGANIZER: Kiri Profil, Tengah Ulasan, Kanan Poster Aktif & Selesai
    public function show($id)
    {
        $organizer = User::where('role', 'organizer')->findOrFail($id);
        $now = Carbon::now();

        // 🌟 LOGIKA EVENT AKTIF: Stok tiket ada DAN acara belum lewat
        $activeEvents = Event::where('user_id', $organizer->id)
            ->where('stock', '>', 0)
            ->where('date', '>=', $now)
            ->latest()
            ->get();

        // 🌟 LOGIKA EVENT SELESAI: Stok tiket habis ATAU acara sudah terlewat
        $pastEvents = Event::where('user_id', $organizer->id)
            ->where(function ($query) use ($now) {
                $query->where('stock', '<=', 0)
                      ->orWhere('date', '<', $now);
            })
            ->latest()
            ->get();

        // Tarik semua ulasan yang ditujukan untuk EO ini
        $reviews = Review::where('organizer_id', $organizer->id)
            ->with(['user', 'event'])
            ->latest()
            ->get();

        return view('organizers.show', compact('organizer', 'activeEvents', 'pastEvents', 'reviews'));
    }

    // 3. HALAMAN FORM REVIEW: Menampilkan form jika lolos validasi
    public function createReview($id)
    {
        $organizer = User::where('role', 'organizer')->findOrFail($id);
        $userId = auth()->id();
        $now = \Carbon\Carbon::now(); // 🌟 Ambil waktu saat ini (Tahun 2026)

        // 🌟 VALIDASI ANTI-SPAM + TANGGAL LEWAT: Hanya memuat event yang sudah SELESAI dilaksanakan
        $eligibleEvents = Event::where('user_id', $organizer->id)
            ->where('date', '<', $now) // 🔥 Syarat mutlak: Tanggal acara harus sudah berlalu
            ->whereHas('transactions', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'success');
            })
            ->get();

        if ($eligibleEvents->isEmpty()) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki riwayat tiket yang sah dari event yang telah selesai diselenggarakan oleh vendor ini.');
        }

        return view('reviews.create', compact('organizer', 'eligibleEvents'));
    }

    // 4. PROSES SIMPAN REVIEW KE DATABASE
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $organizer = User::where('role', 'organizer')->findOrFail($id);
        
        // Pengamanan Lapis 2: Pastikan event yang dikirim via form memang benar milik EO ini
        $event = Event::where('id', $request->event_id)->where('user_id', $organizer->id)->firstOrFail();

        Review::create([
            'user_id'      => auth()->id(),
            'organizer_id' => $organizer->id,
            'event_id'     => $event->id,
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        return redirect()->route('organizers.show', $organizer->id)
                         ->with('success', 'Terima kasih, ulasan Anda berhasil dikirim.');
    }
}