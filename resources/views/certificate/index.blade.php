@extends('layouts.admin')
@section('title', 'E-Certificate Portal Admin')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ openModal: false, selectedCert: {} }">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Daftar E-Sertifikat Terbit</h1>
        <p class="text-slate-500 text-sm">Menampilkan sertifikat peserta yang telah sukses melakukan check-in.</p>
    </div>

    <!-- Grid Card Sertifikat -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($certificates as $cert)
            <!-- Card Item (Klik untuk Trigger Detail via AlpineJS/JS) -->
            <div @click="openModal = true; selectedCert = { 
                    name: '{{ $cert->transaction->customer_name }}', 
                    event: '{{ $cert->transaction->event->title ?? 'Event Eksklusif' }}',
                    number: '{{ $cert->certificate_number }}',
                    previewUrl: '{{ route('admin.certificate.preview', $cert->id) }}', 
                    downloadUrl: '{{ route('admin.certificate.download', $cert->id) }}'
                 }" 
                 class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition duration-200 cursor-pointer border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-amber-50 rounded-lg text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <span class="text-xs font-mono bg-slate-100 text-slate-600 px-2 py-1 rounded">
                        {{ $cert->certificate_number }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 truncate">{{ $cert->transaction->customer_name }}</h3>
                <p class="text-slate-500 text-sm mt-1 truncate">{{ $cert->transaction->event->title ?? 'Modern Web Development' }}</p>
                <div class="mt-4 pt-3 border-t border-slate-100 text-right">
                    <span class="text-xs text-amber-600 font-medium hover:underline">Klik untuk rincian &rarr;</span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-50 border border-dashed border-slate-300 rounded-xl p-12 text-center">
                <p class="text-slate-500">Belum ada sertifikat yang diterbitkan. Lakukan scan tiket terlebih dahulu.</p>
            </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL SERTIFIKAT (Pop Up Dinamis) -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4" x-cloak>
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 border border-slate-200 relative animate-fade-in" @click.away="openModal = false">
            
            <!-- Tombol Close Modal -->
            <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Ikon Sertifikat -->
            <div class="flex justify-center mb-6">
                <div class="p-4 bg-amber-50 rounded-full text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>

            <!-- Informasi Sertifikat Dinamis -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Rincian Sertifikat</h1>
                <p class="text-slate-500 mt-1 text-sm">Nomor: <span class="font-mono font-semibold" x-text="selectedCert.number"></span></p>
                
                <div class="mt-4 p-4 bg-slate-50 rounded-lg text-left border border-slate-100">
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Nama Peserta</div>
                    <div class="text-slate-700 font-medium text-lg" x-text="selectedCert.name"></div>
                    
                    <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mt-3">Nama Event / Kelas</div>
                    <div class="text-slate-700 font-medium" x-text="selectedCert.event"></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="space-y-3">
                <a :href="selectedCert.previewUrl" target="_blank" 
                   class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 text-white font-medium rounded-xl transition duration-200 shadow-md">
                    Lihat Sertifikat
                </a>

                <a :href="selectedCert.downloadUrl" 
                   class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl transition duration-200 shadow-md">
                    Download PDF
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Memuat Alpine.js untuk handle state Modal Pop-up tanpa perlu jQuery/JS rumit -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection