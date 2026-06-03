<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->get();

        return view('admin.partners.index', compact('partners', 'search'));
    }


    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        'website_url' => 'required|url',
        ]);
        $path = $request->file('logo')->store('partners', 'public');
        Partner::create([
        'name' => $request->name,
        'logo_path' => $path, 
        'website_url' => $request->website_url,
        ]);

    return redirect()->back()->with('success', 'Partner berhasil didaftarkan!');
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        $request->validate([
        'name' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        'website_url' => 'required|url',
        ]);

        $logoPath = $partner->logo_path;
        if ($request->hasFile('logo')) {
        
        if ($partner->logo_path && Storage::disk('public')->exists($partner->logo_path)) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $logoPath = $request->file('logo')->store('partners', 'public');
        }
        
        $partner->update([
        'name' => $request->name,
        'logo_path' => $logoPath,
        'website_url' => $request->website_url,
        ]);

        return redirect()->back()->with('success', 'Data partner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}