<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf; // 🌟 Pustaka untuk mencetak PDF sertifikat
use Illuminate\Http\Request;

class UserTicketController extends Controller
{
    // Ambil semua tiket sukses milik user yang sedang aktif login
    public function indexTickets()
    {
        $tickets = Transaction::with('event')
            ->where('user_id', auth()->id())
            ->where('status', 'success') // Proteksi: Hanya tiket lunas yang punya hak tampil
            ->latest()
            ->get();

        return view('user.tickets.index', compact('tickets'));
    }

    // Ambil semua sertifikat yang diterbitkan atas nama transaksi user ini
    public function indexCertificates()
    {
        $certificates = Certificate::whereHas('transaction', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('transaction.event')->latest()->get();

        return view('user.certificates.index', compact('certificates'));
    }

    // Fitur unduh sertifikat langsung dari dashboard user
    public function downloadCertificate($id)
    {
        // Pastikan sertifikat yang diunduh memang benar milik user yang sedang login (Security Check)
        $certificate = Certificate::whereHas('transaction', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('transaction.event')->findOrFail($id);
        
        $pdf = Pdf::loadView('certificate.template', [
            'name' => $certificate->transaction->customer_name,
            'course' => $certificate->transaction->event->title ?? 'Event Eksklusif',
            'date' => $certificate->created_at->translatedFormat('d F Y'),
            'certificate_id' => $certificate->certificate_number
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat - ' . $certificate->transaction->customer_name . '.pdf');
    }
}