@extends('layouts.admin')
@section('title', 'Tambah Vendor')
@section('page_title', 'Registrasi Akun Vendor')

@section('content')
<div class="max-w-xl bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
    <form action="{{ route('admin.organizers.store') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Nama Vendor / Organisasi</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                   class="w-full px-4 py-3 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm focus:outline-indigo-600">
            @error('name')
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Email Login</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   class="w-full px-4 py-3 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm focus:outline-indigo-600">
            @error('email')
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Password Akun</label>
            <input type="password" name="password" required 
                   class="w-full px-4 py-3 border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-xl text-sm focus:outline-indigo-600">
            @error('password')
                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl text-xs hover:bg-indigo-700 transition shadow-sm">Simpan Akun</button>
            <a href="{{ route('admin.organizers.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection