<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
    })->latest()->get();

        return view('admin.partners.index', compact('partners', 'search'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url',
        ]);
        Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url,
        ]);
        return redirect()->route('admin.partners.index')->with('success', 'Partner baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->update([
            'name' => $request->name,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}