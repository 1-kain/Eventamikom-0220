@extends('layouts.admin', ['title' => 'Kelola Event'])

@section('content')
<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Kelola Event</h1>
        <p class="text-slate-500 font-medium">Buat dan atur acara seru Anda di sini.</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
        + Tambah Event Baru
    </a>
</header>

<div class="mb-6">
    <form action="{{ route('admin.events.index') }}" method="GET" class="flex gap-3 max-w-md">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul event..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
        </div>
        <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition">
            Cari
        </button>
        @if($search)
            <a href="{{ route('admin.events.index') }}" class="px-4 py-2.5 bg-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-300 transition flex items-center">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">No</th>
                    <th class="px-8 py-4">Poster</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Harga / Stok</th>
                    <th class="px-8 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($events as $index => $event)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <img src="{{ asset('storage/'.$event->poster_path) }}" class="w-16 h-20 rounded-xl object-cover shadow-sm">
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800">{{ $event->title }}</p>
                        <p class="text-xs text-slate-400">{{ $event->category->name }} • {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400">Stok: {{ $event->stock }}</p>
                    </td>
                    <td class="px-8 py-6 flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-4M17.414 2.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>
                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">
                        Belum ada event terdaftar atau tidak ditemukan kata kunci yang cocok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection