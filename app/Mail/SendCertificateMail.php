<?php

namespace App\Mail;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;

    /**
     * 1. Tangkap data sertifikat saat dipicu dari Controller
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * 2. Tentukan Subjek/Judul Email yang Masuk ke Inbox
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Certificate: ' . ($this->certificate->transaction->event->title ?? 'Event Eksklusif'),
        );
    }

    /**
     * 3. Arahkan ke Tampilan Surat Pengantar
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate',
        );
    }

    /**
     * 4. 🌟 PROSES MERENDER & MELAMPIRKAN PDF
     */
    public function attachments(): array
    {
        // Render view template sertifikat menjadi data biner PDF secara real-time
        $pdf = Pdf::loadView('certificate.template', [
            'name' => $this->certificate->transaction->customer_name,
            'course' => $this->certificate->transaction->event->title ?? 'Event Eksklusif',
            'date' => $this->certificate->created_at->translatedFormat('d F Y'),
            'certificate_id' => $this->certificate->certificate_number
        ])->setPaper('a4', 'landscape');

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Sertifikat-' . $this->certificate->certificate_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}