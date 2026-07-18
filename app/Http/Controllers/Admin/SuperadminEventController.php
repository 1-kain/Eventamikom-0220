<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class SuperadminEventController extends Controller
{
    public function index()
    {
        // Kita tambahkan pencarian juga agar UI hybrid lu bekerja untuk Superadmin
        $search = request('search');
        
        $events = Event::with(['category', 'user'])
            ->when($search, function($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        // 🌟 KUNCI: Kita arahkan ke file 'global', bukan 'index' lagi
        return view('admin.events.global', compact('events', 'search'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $event->update($request->only(['title', 'price', 'date', 'category_id', 'description', 'location']));

        return redirect()->route('admin.events.index')->with('success', 'Event milik vendor berhasil dimoderasi.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus oleh Admin.');
    }
}