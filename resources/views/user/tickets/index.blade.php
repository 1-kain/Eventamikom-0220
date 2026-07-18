@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tiket Saya</h1>
        <p class="text-slate-500 text-sm mt-1">Tunjukkan QR Code di bawah ini kepada petugas gerbang saat masuk acara.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($tickets as $ticket)
            <!-- Kartu Tiket Digital -->
            <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden flex flex-col sm:flex-row transition duration-300 {{ $ticket->is_scanned === 'YES' ? 'opacity-60 bg-slate-50' : 'hover:shadow-md' }}">
                
                <!-- Sisi Kiri: Informasi Event -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="px-3 py-1 text-[10px] font-black tracking-wider uppercase rounded-full {{ $ticket->is_scanned === 'YES' ? 'bg-slate-200 text-slate-600' : 'bg-emerald-50 text-emerald-600' }}">
                            {{ $ticket->is_scanned === 'YES' ? 'Sudah Digunakan' : 'Tiket Aktif' }}
                        </span>
                        <h3 class="font-bold text-xl text-slate-800 mt-3 leading-snug">
                            {{ $ticket->event->title ?? 'Event Eksklusif' }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">
                            Kode: <span class="font-mono font-bold text-slate-700">{{ $ticket->ticket_code }}</span>
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500">
                        Dipesan pada: {{ $ticket->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </div>
                </div>

                <!-- Sisi Kanan: Generator QR Code -->
                <div class="bg-slate-50 p-6 flex flex-col items-center justify-center border-t sm:border-t-0 sm:border-l border-dashed border-slate-200 min-w-[180px]">
                    @if($ticket->is_scanned === 'YES')
                        <!-- Indikator Stempel Bekas -->
                        <div class="w-24 h-24 rounded-full border-4 border-slate-300 flex items-center justify-center text-slate-400 font-black tracking-widest text-xs uppercase transform -rotate-12 select-none">
                            VOIDED
                        </div>
                    @else
                        <!-- 🌟 MESIN CETAK QR CODE LOKAL -->
                        <div class="p-2 bg-white rounded-2xl shadow-inner border border-slate-100">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(1)->generate($ticket->ticket_code) !!}                        </div>
                        <span class="text-[10px] text-slate-400 mt-2 tracking-wide font-medium">Scan Me</span>
                    @endif
                </div>

            </div>
            @empty
            <!-- Kondisi jika dompet tiket kosong -->
            <div class="col-span-full bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-bold">🎫</div>
                <h3 class="font-bold text-lg text-slate-800">Belum Ada Tiket</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-sm mx-auto">Kamu belum membeli tiket event apa pun. Yuk, jelajahi event seru di AmikomEventHub!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection