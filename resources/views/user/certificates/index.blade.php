@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Sertifikat Saya</h1>
        <p class="text-slate-500 text-sm mt-1">Apresiasi atas partisipasi aktifmu dalam mengembangkan skill di berbagai event.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($certificates as $cert)
            <!-- Kartu Sertifikat Elegan -->
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition duration-300">
                <div>
                    <!-- Icon Badge -->
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold mb-4">
                        🎓
                    </div>
                    <h3 class="font-bold text-lg text-slate-800 leading-snug">
                        {{ $cert->transaction->event->title ?? 'Event Eksklusif' }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 font-mono">
                        No: {{ $cert->certificate_number }}
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">
                        Rilis: {{ $cert->created_at->translatedFormat('d M Y') }}
                    </span>
                    <!-- 🌟 TOMBOL UNDUH PDF LANGSUNG -->
                    <a href="{{ route('user.certificates.download', $cert->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition duration-200 shadow-sm shadow-indigo-100 flex items-center gap-1">
                        Unduh PDF
                    </a>
                </div>
            </div>
        @empty
            <!-- Kondisi jika lemari sertifikat kosong -->
            <div class="col-span-full bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-bold">🎓</div>
                <h3 class="font-bold text-lg text-slate-800">Belum Ada Sertifikat</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-sm mx-auto">Sertifikat otomatis terbit di sini setelah kamu menghadiri acara dan tiketmu berhasil di-scan oleh panitia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection