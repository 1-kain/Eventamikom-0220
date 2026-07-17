@extends('layouts.admin')
@section('title', 'Gate Scanner - Pengecekan Tiket')

@section('content')
<!-- Tidak ada lagi CDN Bootstrap dan CSS kustom di sini. Semua di-handle Tailwind. -->

<!-- WADAH UTAMA: Memastikan posisi tepat di tengah (Flexbox) -->
<div class="flex flex-col items-center justify-center min-h-[80vh] w-full px-4 py-8">
    
    <!-- KOTAK PEMBUNGKUS (Maksimal lebar lg / ~512px) -->
    <div class="w-full max-w-lg">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">Gate Check-in</h3>
            <p class="text-sm text-slate-500 mt-1">Arahkan QR Code ke kamera atau input manual</p>
        </div>

        <!-- Kartu Scanner -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6">
                
                <!-- Notifikasi Hasil Scan -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-center font-medium mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-center font-medium mb-6">
                        {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-center font-medium mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Container Kamera Scanner -->
                <!-- Kita beri class styling agar area kamera tetap rapi -->
                <div id="reader" class="mb-6 rounded-xl overflow-hidden border border-slate-200 bg-slate-50"></div>

                <!-- Form Input Manual -->
                <form action="{{ route('admin.scan.check') }}" method="POST" id="ticket_form">
                    @csrf
                    <div class="mb-4">
                        <input type="text" 
                               name="ticket_code" 
                               id="ticket_code_input"
                               class="w-full bg-slate-50 border border-transparent focus:border-slate-300 focus:bg-white focus:ring-2 focus:ring-slate-100 rounded-xl text-center py-3 text-lg transition-all duration-200 outline-none" 
                               placeholder="Ketik kode tiket..." 
                               autofocus 
                               required>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 px-4 rounded-xl tracking-wide transition-colors duration-200">
                        Validasi Manual
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

<!-- Library HTML5 QR Code -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Konfigurasi Scanner kamera
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA] 
            }, 
            false
        );

        // Fungsi saat QR Code berhasil dibaca
        function onScanSuccess(decodedText, decodedResult) {
            // 1. Hentikan pemindaian agar tidak terjadi double-submit
            html5QrcodeScanner.clear();
            
            // 2. Isi kolom input dengan hasil scan
            document.getElementById('ticket_code_input').value = decodedText;
            
            // 3. Kirim form secara otomatis
            document.getElementById('ticket_form').submit();
        }

        function onScanFailure(error) {
            // Biarkan kosong agar pemindai terus mencari QR Code
        }

        // Jalankan scanner
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endsection