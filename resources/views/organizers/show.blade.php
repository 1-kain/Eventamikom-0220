@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Grid 3 Kolom Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative">

        <!-- 1. KIRI: Profil Organizer (Ambil 3 Kolom) -->
        <div class="lg:col-span-3">
            <!-- Posisi sticky agar tetap terlihat saat tengah di-scroll -->
            <div class="sticky top-28 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center">
                <!-- Kotak Fleksibel Logo (Otomatis Fit) -->
                <div class="w-full aspect-square bg-indigo-50 rounded-xl flex items-center justify-center text-5xl font-bold text-indigo-600 mb-4 overflow-hidden">
                    {{ strtoupper(substr($organizer->name, 0, 2)) }}
                </div>
                
                <h2 class="text-xl font-bold text-slate-900 mb-2">{{ $organizer->name }}</h2>
                
                <!-- Deskripsi Singkat -->
                <p class="text-slate-500 text-sm leading-relaxed">
                    Penyelenggara acara resmi yang telah bermitra dengan AmikomEventHub. Berdedikasi untuk memberikan pengalaman event terbaik.
                </p>
            </div>
        </div>

        <!-- 2. TENGAH: Ulasan Pengunjung (Ambil 6 Kolom) -->
        <div class="lg:col-span-6 flex flex-col h-full min-h-[500px]">
            <h3 class="text-2xl font-bold text-slate-900 mb-6">Ulasan Pengunjung</h3>
            
            <!-- Tempat Ulasan Berjejer -->
            <div class="space-y-4 flex-grow pb-32">
                @forelse($reviews as $review)
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-bold text-slate-800">{{ $review->user->name }}</div>
                            <div class="text-yellow-400 font-bold tracking-widest">
                                {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                            </div>
                        </div>
                        <div class="text-xs text-indigo-600 font-bold mb-3 uppercase tracking-wide">
                            Event: {{ $review->event->title }}
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 italic">
                        Belum ada ulasan untuk penyelenggara ini.
                    </div>
                @endforelse
            </div>

            <!-- 🌟 TOMBOL STICKY DI BAWAH TENGAH -->
            <div class="sticky bottom-8 mt-auto w-full z-10">
                <div class="bg-white/80 backdrop-blur-md p-4 rounded-2xl border border-indigo-100 shadow-[0_-10px_40px_-15px_rgba(79,70,229,0.3)]">
                    <a href="{{ route('review.create', $organizer->id) }}" class="block w-full text-center py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition transform hover:-translate-y-1">
                        Ayo Tulis Review Anda
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. KANAN: Poster Event (Ambil 3 Kolom) -->
        <div class="lg:col-span-3 space-y-10">
            
            <!-- Baris Atas: Event Aktif -->
            <div>
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 
                    Sedang Berlangsung
                </h3>
                <div class="space-y-4">
                    @php $hasActivePoster = false; @endphp
                    
                    @foreach($activeEvents as $event)
                        @if($event->poster_path)
                            @php $hasActivePoster = true; @endphp
                            <a href="{{ route('event.show', $event->id) }}" class="block rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-full h-auto object-cover hover:scale-105 transition duration-500">
                            </a>
                        @endif
                    @endforeach

                    @if(!$hasActivePoster)
                        <p class="text-sm text-slate-400 italic">Tidak ada poster event aktif.</p>
                    @endif
                </div>
            </div>

            <!-- Baris Bawah: Event Selesai -->
            <div>
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> 
                    Selesai
                </h3>
                <div class="space-y-4">
                    @php $hasPastPoster = false; @endphp
                    
                    @foreach($pastEvents as $event)
                        @if($event->poster_path)
                            @php $hasPastPoster = true; @endphp
                            <!-- Efek visual redup (grayscale/opacity) untuk event selesai, bisa diklik tapi di detail event nanti gak bisa dibeli -->
                            <a href="{{ route('event.show', $event->id) }}" class="block rounded-xl overflow-hidden shadow-sm opacity-70 hover:opacity-100 transition">
                                <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-full h-auto object-cover grayscale hover:grayscale-0 transition duration-500">
                            </a>
                        @endif
                    @endforeach

                    @if(!$hasPastPoster)
                        <p class="text-sm text-slate-400 italic">Tidak ada riwayat poster event.</p>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection