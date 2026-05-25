<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua QR Code Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        /* Mengatur agar layout membentuk grid (kotak-kotak) */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            justify-content: center;
        }
        /* Desain kartu pelajar mini */
        .id-card {
            background-color: #fff;
            border: 1px dashed #333; /* Garis putus-putus biar gampang digunting */
            border-radius: 8px;
            width: 200px;
            padding: 15px;
            text-align: center;
            box-sizing: border-box;
            page-break-inside: avoid; /* Mencegah kartu terpotong di halaman selanjutnya saat di-print */
        }
        .header {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        /* Ukuran QR diperkecil biar proporsional */
        .qr-container img {
            width: 100px !important;
            height: 100px !important;
            margin-bottom: 10px;
        }
        .nis {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .nama {
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .kelas {
            font-size: 10px;
            color: #555;
        }

        /* Settingan Khusus Saat Masuk Kertas Print */
        @media print {
            body { background-color: #fff; padding: 0; }
            .grid-container {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* Paksa 3 kolom per baris di kertas A4 */
                gap: 10px;
            }
            .id-card {
                border: 1px solid #000; /* Garis solid saat dicetak */
                box-shadow: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="grid-container">
        @foreach($siswas as $siswa)
        <div class="id-card">
            <div class="header">
                KARTU PRESENSI QR<br>SMK PEMBANGUNAN
            </div>

            <div class="qr-container">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $siswa->nis }}" alt="QR Code">
            </div>

            <div class="nis">{{ $siswa->nis }}</div>
            <div class="nama">{{ $siswa->nama_siswa }}</div>
            <div class="kelas">{{ $siswa->kelas ?? 'Kelas -' }}</div>
        </div>
        @endforeach
    </div>

</body>
</html>
