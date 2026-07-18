<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Certificate;
use App\Mail\SendCertificateMail;       // 🌟 IMPORT: Amplop surat digital kita
use Illuminate\Support\Facades\Mail;   // 🌟 IMPORT: Driver pengirim email
use Illuminate\Support\Facades\Log;    // 🌟 IMPORT: Pencatat buku laporan eror
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index'); 
    }

    public function check(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);
        
        $ticketCode = $request->input('ticket_code');
        $user = auth()->user();

        // ISOLASI DATA: Saring tiket agar bouncer hanya mengenali tiket milik eventnya sendiri
        $transaction = Transaction::where('ticket_code', $ticketCode)
            ->when($user->role === 'organizer', function($query) use ($user) {
                return $query->whereHas('event', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->first();

        // Skenario 1: Tiket tidak ditemukan
        if (!$transaction) {
            return back()->with('error', 'Kode tiket tidak ditemukan untuk event Anda.');
        }

        // Skenario 2: Tiket ditemukan, TAPI belum lunas
        if ($transaction->status !== 'success') {
            return back()->with('error', 'Harap lunasi pembayaran terlebih dahulu. Status saat ini: ' . $transaction->status);
        }

        // Skenario 3: Tiket lunas, TAPI sudah pernah di-scan sebelumnya
        if (strtoupper($transaction->is_scanned) === 'YES') {
            return back()->with('error', 'Tiket sudah pernah digunakan untuk check-in.');
        }

        // Skenario 4: Tiket valid, lunas, dan belum pernah digunakan
        if (strtolower($transaction->is_scanned) === 'no' || !$transaction->is_scanned) {
            
            // Perbarui status kehadiran menjadi YES
            $transaction->is_scanned = 'YES';
            $transaction->save();

            // OTOMATIS GENERATE SERTIFIKAT KE DATABASE
            $existingCertificate = Certificate::where('transaction_id', $transaction->id)->first();
            
            if (!$existingCertificate) {
                // 1. Simpan hasil pembuatan data ke dalam variabel agar instansinya bisa dipakai
                $newCertificate = Certificate::create([
                    'transaction_id' => $transaction->id,
                    'certificate_number' => 'CERT-' . strtoupper(Str::random(5)) . '-' . date('Ymd')
                ]);

                // 2. Muat ulang relasi data transaksi & event agar terbaca utuh oleh mesin PDF
                $newCertificate->load('transaction.event');

                // 3. Panggil kurir ekspedisi untuk menembak email langsung ke Gmail SMTP
                try {
                    Mail::to($transaction->customer_email)->send(new SendCertificateMail($newCertificate));
                } catch (\Exception $e) {
                    // Proteksi: Jika koneksi SMTP down/timeout, proses check-in tidak akan gagalkan transaksi database
                    Log::error('Gagal mengirim email sertifikat: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Check-in berhasil! E-Certificate untuk ' . $transaction->customer_name . ' telah diterbitkan dan dikirim ke email.');
        }
        
        return back()->with('error', 'Terjadi kesalahan sistem pada validasi tiket.');
    }
}