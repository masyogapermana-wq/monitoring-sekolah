<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $siswa->nama_siswa }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f4f4f4;
        }
        /* Styling biar bentuknya kayak ID Card / Kartu Pelajar */
        .id-card {
            background-color: #fff;
            border: 2px solid #333;
            border-radius: 10px;
            width: 250px;
            height: 350px;
            display: inline-block;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        /* Sesuaikan dengan cara aplikasi lu nampilin gambar QR Code */
        .qr-container img, .qr-container svg {
            width: 150px !important;
            height: 150px !important;
            margin-bottom: 15px;
        }
        .nis {
            font-size: 18px;
            letter-spacing: 2px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }
        .nama {
            font-size: 16px;
            text-transform: uppercase;
        }

        /* Settingan pas di-print biar backgroundnya hilang */
        @media print {
            body { padding-top: 0; background-color: #fff; }
            .id-card { box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="id-card">
        <div class="header">
            KARTU PRESENSI QR<br>SMK PEMBANGUNAN
        </div>

        <div class="qr-container">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $siswa->nis }}" alt="QR Code">
        </div>

        <div class="nis">{{ $siswa->nis }}</div>
        <div class="nama">{{ $siswa->nama_siswa }}</div>
    </div>

</body>
</html>
