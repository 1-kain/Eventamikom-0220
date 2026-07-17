<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index'); // Sesuaikan dengan path view scan kamu
    }

    public function check(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);
        
        $ticketCode = $request->input('ticket_code');

        // 1. Cari data di tabel transactions berdasarkan ticket_code
        $transaction = Transaction::where('ticket_code', $ticketCode)->first();

        // Skenario 1: Tiket tidak ditemukan
        if (!$transaction) {
            return back()->with('error', 'Kode tiket tidak ditemukan.');
        }

        // Skenario 2: Tiket ditemukan, TAPI belum lunas (status bukan 'berhasil')
        if ($transaction->status !== 'success') {
            return back()->with('error', 'Harap lunasi pembayaran terlebih dahulu. Status saat ini: ' . $transaction->status);
        }

        // Skenario 3: Tiket lunas, TAPI sudah pernah di-scan sebelumnya
        if ($transaction->is_scanned === 'YES') {
            return back()->with('error', 'Tiket sudah pernah digunakan untuk check-in.');
        }

        // Skenario 4: Tiket valid, lunas, dan belum pernah digunakan
        if ($transaction->is_scanned === 'No') {
            
            // Perbarui status kehadiran menjadi YES
            $transaction->is_scanned = 'YES';
            $transaction->save();

            // OTOMATIS GENERATE SERTIFIKAT KE DATABASE
            // Kita cek dulu mencegah duplikasi sertifikat
            $existingCertificate = Certificate::where('transaction_id', $transaction->id)->first();
            
            if (!$existingCertificate) {
                Certificate::create([
                    'transaction_id' => $transaction->id,
                    'certificate_number' => 'CERT-' . strtoupper(Str::random(5)) . '-' . date('Ymd')
                ]);
            }

            return back()->with('success', 'Check-in berhasil! E-Certificate untuk ' . $transaction->customer_name . ' telah diterbitkan.');
        }
    }
}