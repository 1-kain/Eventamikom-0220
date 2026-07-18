<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganizerController extends Controller
{
    public function index()
    {
        $organizers = User::where('role', 'organizer')->latest()->get();
        return view('admin.organizers.index', compact('organizers'));
    }

    public function create()
    {
        return view('admin.organizers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // ✔️ Sudah diperbaiki dari max::255
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'organizer',
        ]);

        return redirect()->route('admin.organizers.index')->with('success', 'Vendor berhasil didaftarkan.');
    }

    public function edit($id)
    {
        $organizer = User::where('role', 'organizer')->findOrFail($id);
        return view('admin.organizers.edit', compact('organizer'));
    }

    public function update(Request $request, $id)
    {
        $organizer = User::where('role', 'organizer')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $organizer->id,
            'password' => 'nullable|string|min:8', // Password opsional saat edit
        ]);

        $organizer->name = $request->name;
        $organizer->email = $request->email;
        
        if ($request->filled('password')) {
            $organizer->password = Hash::make($request->password);
        }

        $organizer->save();

        return redirect()->route('admin.organizers.index')->with('success', 'Data Vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organizer = User::where('role', 'organizer')->findOrFail($id);
        $organizer->delete();

        return redirect()->route('admin.organizers.index')->with('success', 'Vendor berhasil dihapus dari sistem.');
    }
}