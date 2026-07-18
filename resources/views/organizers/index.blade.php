@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Bagian Header Halaman -->
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Mitra Penyelenggara</h1>
        <p class="text-slate-500 mt-2">Jelajahi berbagai event seru dari organizer partner kami.</p>
    </div>

    <!-- Grid Direktori Organizer -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        <!-- Looping data dari Controller -->
        @forelse($organizers as $eo)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg hover:border-indigo-300 transition duration-300 group flex flex-col justify-between">
                
                <div>
                    <!-- Avatar/Logo Placeholder -->
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-4 group-hover:scale-105 transition transform">
                        {{ strtoupper(substr($eo->name, 0, 2)) }}
                    </div>
                    
                    <!-- Informasi Singkat -->
                    <h3 class="text-lg font-bold text-slate-900 mb-1 truncate" title="{{ $eo->name }}">
                        {{ $eo->name }}
                    </h3>
                    <p class="text-slate-500 text-sm mb-6">
                        Bergabung {{ $eo->created_at->diffForHumans() }}
                    </p>
                </div>

                <!-- Tombol Navigasi ke Detail -->
                <a href="{{ route('organizers.show', $eo->id) }}" class="inline-block w-full text-center px-4 py-2.5 bg-slate-50 group-hover:bg-indigo-600 group-hover:text-white text-indigo-600 rounded-xl font-semibold transition duration-300">
                    Lihat Profil
                </a>
            </div>
        @empty
            <!-- State jika database kosong -->
            <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 text-slate-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="text-lg font-medium">Belum ada penyelenggara event yang terdaftar saat ini.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection