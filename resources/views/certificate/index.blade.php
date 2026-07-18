@extends('layouts.admin')
@section('title', 'Kelola Sertifikat')
@section('page_title', 'Daftar Sertifikat Peserta')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b">
        <h3 class="font-black text-xl">Sertifikat Tersedia</h3>
        <p class="text-sm text-slate-400 mt-1">Daftar sertifikat peserta yang telah berhasil melakukan check-in tiket.</p>
    </div> 

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">No. Sertifikat</th>
                    <th class="px-8 py-4">Nama Peserta</th>
                    <th class="px-8 py-4">Nama Event</th>
                    <th class="px-8 py-4">Tgl Rilis</th>
                    <th class="px-8 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($certificates as $cert)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 text-sm font-bold text-slate-700">
                        {{ $cert->certificate_number }}
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-sm text-slate-800 uppercase">{{ $cert->transaction->customer_name }}</p>
                        <p class="text-xs text-slate-400">{{ $cert->transaction->customer_email }}</p>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-600 font-medium">
                        {{ $cert->transaction->event->title ?? 'Event Eksklusif' }}
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500">
                        {{ $cert->created_at->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-8 py-6 text-center whitespace-nowrap space-x-2">
                        <!-- Tombol Preview (Buka di Tab Baru) -->
                        <a href="{{ route('organizer.certificate.preview', $cert->id) }}" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-100 transition inline-block">
                            Pratinjau PDF
                        </a>
                        <!-- Tombol Download Langsung -->
                        <a href="{{ route('organizer.certificate.download', $cert->id) }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition inline-block">
                            Unduh
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-500">
                        Belum ada sertifikat yang digenerate. Pastikan peserta sudah melakukan check-in.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection