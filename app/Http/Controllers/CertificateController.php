<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    // 1. MENAMPILKAN DAFTAR CARD SERTIFIKAT DI DASHBOARD
    public function index()
    {
        $user = auth()->user();

        // 🌟 ISOLASI DATA: Saring sertifikat berdasarkan kepemilikan event jika user adalah organizer
        $certificates = Certificate::with('transaction.event')
            ->when($user->role === 'organizer', function($query) use ($user) {
                return $query->whereHas('transaction.event', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->get();

        return view('certificate.index', compact('certificates'));
    }

    // 2. FUNGSI UNTUK PRATINJAU (VIEW) DI BROWSER BERDASARKAN ID
    public function preview($id)
    {
        $certificate = Certificate::with('transaction.event')->findOrFail($id);
        $user = auth()->user();

        // 🌟 PROTEKSI KEAMANAN: Blokir akses jika organizer mencoba mengintip sertifikat event lain
        if ($user->role === 'organizer' && $certificate->transaction->event->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat sertifikat ini.');
        }

        $data = [
            'name'           => $certificate->transaction->customer_name,
            'course'         => $certificate->transaction->event->title ?? 'Event Eksklusif',
            'date'           => $certificate->created_at->translatedFormat('d F Y'),
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
        $certificate = Certificate::with('transaction.event')->findOrFail($id);
        $user = auth()->user();

        // 🌟 PROTEKSI KEAMANAN: Blokir akses jika organizer mencoba mengunduh sertifikat event lain
        if ($user->role === 'organizer' && $certificate->transaction->event->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh sertifikat ini.');
        }

        $data = [
            'name'           => $certificate->transaction->customer_name,
            'course'         => $certificate->transaction->event->title ?? 'Event Eksklusif',
            'date'           => $certificate->created_at->translatedFormat('d F Y'),
            'certificate_id' => $certificate->certificate_number
        ];

        // Catatan: Di kode aslimu method ini memanggil 'admin.certificate.template', 
        // pastikan file view ini memang ada dan berbeda dengan template preview jika diperlukan.
        $pdf = Pdf::loadView('admin.certificate.template', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat_' . str_replace(' ', '_', $data['name']) . '.pdf');
    }
}