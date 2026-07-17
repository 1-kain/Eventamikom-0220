<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        /* Mengatur margin kertas PDF menjadi 0 agar background penuh */
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0px;
            padding: 0px;
            background-color: #f7f9fa;
        }
        /* Container utama sertifikat */
        .certificate-container {
            width: 297mm;  /* Ukuran A4 Landscape */
            height: 210mm;
            position: relative;
            box-sizing: border-box;
            border: 20px solid #1a252f; /* Bingkai luar elegan */
            padding: 40px;
        }
        .inner-border {
            border: 5px solid #b3924e; /* Bingkai emas dalam */
            height: 100%;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            padding-top: 50px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a252f;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 42px;
            font-weight: bold;
            color: #b3924e;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 18px;
            font-style: italic;
            color: #555;
            margin-bottom: 30px;
        }
        .name {
            font-size: 32px;
            font-weight: bold;
            color: #1a252f;
            text-decoration: underline;
            margin-bottom: 10px;
        }
        .reason {
            font-size: 16px;
            color: #666;
            width: 70%;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }
        /* Mengatur posisi tanda tangan di bawah */
        .footer-section {
            position: absolute;
            bottom: 60px;
            width: 100%;
            left: 0;
            text-align: center;
        }
        .signature-box {
            display: inline-block;
            width: 200px;
            margin: 0 100px;
            text-align: center;
        }
        .line {
            border-bottom: 2px solid #1a252f;
            margin-bottom: 5px;
        }
        .id-cert {
            position: absolute;
            bottom: 25px;
            left: 45px;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="certificate-container">
        <div class="inner-border">
            
            <div class="logo">ACADEMY TECH</div>
            
            <div class="title">SERTIFIKAT PENGHARGAAN</div>
            <div class="subtitle">Diberikan Kepada:</div>
            
            <div class="name">{{ $name }}</div>
            
            <div class="reason">
                Atas partisipasi dan kelulusannya dalam menyelesaikan pelatihan intensif materi 
                <strong>"{{ $course }}"</strong> yang diselenggarakan secara daring pada tanggal {{ $date }}.
            </div>

            <!-- Bagian Tanda Tangan -->
            <div class="footer-section">
                <div class="signature-box">
                    <div style="height: 60px;"></div> <!-- Space untuk tanda tangan fisik/digital -->
                    <div class="line"></div>
                    <strong>Yusuf Assegaf</strong><br>
                    <span>Direktur Utama</span>
                </div>
                
                <div class="signature-box">
                    <div style="height: 60px;"></div>
                    <div class="line"></div>
                    <strong>Rina Permata</strong><br>
                    <span>Instruktur Utama</span>
                </div>
            </div>

            <!-- ID Sertifikat -->
            <div class="id-cert">No. Sertifikat: {{ $certificate_id }}</div>

        </div>
    </div>

</body>
</html>