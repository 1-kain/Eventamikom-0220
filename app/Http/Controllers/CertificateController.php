<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan namespace ini sesuai instalasi laravel-dompdf Anda

class CertificateController extends Controller
{
    // 1. MENAMPILKAN DAFTAR CARD SERTIFIKAT DI DASHBOARD
    public function index()
    {
        // Mengambil semua sertifikat yang terbit beserta relasi transaksi dan event-nya
        $certificates = Certificate::with('transaction.event')->latest()->get();

        // Mengirimkan variabel $certificates ke view
        return view('certificate.index', compact('certificates'));
    }

    // 2. FUNGSI UNTUK PRATINJAU (VIEW) DI BROWSER BERDASARKAN ID
    public function preview($id)
    {
        // Cari sertifikat atau lempar 404 jika tidak ada
        $certificate = Certificate::with('transaction.event')->findOrFail($id);

        // Petakan data dari database ke dalam array untuk template PDF
        $data = [
            'name'           => $certificate->transaction->customer_name,
            'course'         => $certificate->transaction->event->title ?? 'Event Eksklusif',
            'date'           => $certificate->created_at->translatedFormat('d F Y'), // Menggunakan format tanggal terlokalisasi
            'certificate_id' => $certificate->certificate_number
        ];

        $pdf = Pdf::loadView('certificate.template', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat_' . str_replace(' ', '_', $data['name']) . '.pdf', [
            'Attachment' => false
        ]);
    }

    // 3. FUNGSI UNTUK UNDUH LANGSUNG BERDASARKAN ID
    public function download($id)
    {
        // Cari sertifikat atau lempar 404 jika tidak ada
        $certificate = Certificate::with('transaction.event')->findOrFail($id);

        $data = [
            'name'           => $certificate->transaction->customer_name,
            'course'         => $certificate->transaction->event->title ?? 'Event Eksklusif',
            'date'           => $certificate->created_at->translatedFormat('d F Y'),
            'certificate_id' => $certificate->certificate_number
        ];

        $pdf = Pdf::loadView('admin.certificate.template', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat_' . str_replace(' ', '_', $data['name']) . '.pdf');
    }
}