@extends('layouts.admin', ['title' => 'Edit Data Vendor'])

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Data Vendor</h1>
        <p class="text-slate-500 mt-1">Ubah informasi akun dan hak akses milik partner organizer.</p>
    </div>
    <a href="{{ route('admin.organizers.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- PENTING: Target form diarahkan ke method UPDATE dengan spoofing method PUT -->
    <form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" class="p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Input Nama -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-bold text-slate-700">Nama Vendor / Perusahaan</label>
            <input type="text" name="name" id="name" 
                   value="{{ old('name', $organizer->name) }}" 
                   class="w-full px-4 py-3 rounded-xl border @error('name') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror outline-none transition" 
                   placeholder="Masukkan nama resmi vendor" required>
            @error('name')
                <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-bold text-slate-700">Alamat Email Resmi</label>
            <input type="email" name="email" id="email" 
                   value="{{ old('email', $organizer->email) }}" 
                   class="w-full px-4 py-3 rounded-xl border @error('email') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror outline-none transition" 
                   placeholder="nama@perusahaan.com" required>
            @error('email')
                <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="border-slate-100 my-2">

        <!-- Input Password (Opsional saat Edit) -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-bold text-slate-700">Password Baru (Opsional)</label>
            <input type="password" name="password" id="password" 
                   class="w-full px-4 py-3 rounded-xl border @error('password') border-rose-500 ring-1 ring-rose-500 @else border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @enderror outline-none transition" 
                   placeholder="••••••••">
            <p class="text-xs text-slate-400 font-medium mt-1">
                💡 Biarkan kolom password ini kosong jika vendor tidak ingin mengganti password lamanya.
            </p>
            @error('password')
                <p class="text-xs font-semibold text-rose-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi Form -->
        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.organizers.index') }}" class="px-5 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                Batalkan
            </a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-sm hover:shadow transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection