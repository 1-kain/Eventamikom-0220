<!DOCTYPE html>
<html>
<head>
    <title>E-Certificate Anda</title>
</head>
<body style="font-family: sans-serif; color: #334155; line-height: 1.6;">
    <h2>Halo, {{ $certificate->transaction->customer_name }}!</h2>
    <p>Terima kasih telah hadir dan berpartisipasi aktif dalam event <strong>{{ $certificate->transaction->event->title ?? 'Event Eksklusif' }}</strong>.</p>
    <p>Sebagai bentuk apresiasi, kami telah menerbitkan E-Certificate resmi untuk Anda dengan nomor seri <code>{{ $certificate->certificate_number }}</code>.</p>
    <p>Berkas sertifikat telah kami **lampirkan langsung dalam bentuk PDF** di bawah email ini. Silakan unduh dan simpan dengan baik.</p>
    <br>
    <p>Salam hangat,<br><strong>Panitia Penyelenggara</strong></p>
</body>
</html>