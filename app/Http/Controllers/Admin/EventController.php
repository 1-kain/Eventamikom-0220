<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request) 
    {
        $search = $request->query('search');
        $user = auth()->user();

        // ISOLASI DATA: Saring event jika yang login adalah organizer
        $events = Event::with('category')
            ->when($user->role === 'organizer', function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->when($search, function($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        // FIX: Keduanya diarahkan ke view yang sama karena view ini sudah dinamis
        return view('admin.events.index', compact('events', 'search'));
    }

    public function create() 
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request) 
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required|date',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['user_id'] = auth()->id();

        Event::create($data);
        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event) 
    {
        // PROTEKSI KEAMANAN: Cegah organizer lain mengedit data yang bukan miliknya
        if (auth()->user()->role === 'organizer' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah event ini.');
        }

        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event) 
    {
        if (auth()->user()->role === 'organizer' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk memperbarui event ini.');
        }

        $data = $request->validate([
            'category_id' => 'required',
            'title'       => 'required',
            'description' => 'required',
            'date'        => 'required',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event) 
    {
        if (auth()->user()->role === 'organizer' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus event ini.');
        }

        if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus.');
    }
}