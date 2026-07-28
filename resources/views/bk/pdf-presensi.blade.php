<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        .cetak-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* Kop Surat */
        .kop-surat {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .kop-logo {
            display: table-cell;
            width: 18%;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo img {
            width: 100px;
        }

        .kop-teks {
            display: table-cell;
            width: 82%;
            vertical-align: middle;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }

        .kop-teks p {
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .kop-yayasan {
            font-size: 14px;
            text-transform: uppercase;
        }

        .kop-sekolah {
            font-size: 18px;
            font-weight: bold;
            margin: 4px 0 !important;
            text-transform: uppercase;
        }

        .kop-jurusan,
        .kop-npsn,
        .kop-alamat,
        .kop-kontak {
            font-size: 12px;
        }

        .garis-kop {
            border: none;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-bottom: 20px;
        }

        .judul-laporan {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 20px;
        }

        .info-tanggal {
            font-size: 14px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-size: 13px;
        }

        table th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td.rata-kiri {
            text-align: left;
        }

         /* 6. Kolom Tanda Tangan */
        .tanda-tangan {
            width: 100%;
            margin-top: 50px;
        }

        .ttd-kanan {
            float: right;
            width: 300px;
            text-align: center;
            font-size: 14px;
        }

        .ttd-kanan p {
            margin: 0;
            margin-bottom: 5px;
        }
        .ttd-spasi {
            margin-bottom: 70px !important;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="cetak-container">

        <!-- KOP SURAT -->
        <div class="kop-surat">
            <div class="kop-logo">
                @php
                    /*
                     * Sudah dites: file PNG-nya valid, base64 juga valid
                     * (terbukti muncul di test.php).
                     * Jadi di sini kita pakai cara yang sama persis,
                     * tinggal disesuaikan nama file jadi .png
                     */
                    $base64Logo = null;
                    $errorLogo = null;

                    // GANTI ke nama file PNG yang baru kalau beda
                    $pathLogo = public_path('images/logosekolah.png');

                    if (!file_exists($pathLogo)) {
                        $errorLogo = "File tidak ditemukan: " . $pathLogo;
                    } else {
                        $dataLogo = file_get_contents($pathLogo);
                        if ($dataLogo === false) {
                            $errorLogo = "Gagal membaca file (cek permission).";
                        } else {
                            $mimeType = mime_content_type($pathLogo) ?: 'image/png';
                            $base64Logo = 'data:' . $mimeType . ';base64,' . base64_encode($dataLogo);
                        }
                    }
                @endphp

                @if($base64Logo)
                    <img src="{{ $base64Logo }}" alt="Logo Sekolah" width="100">
                @else
                    <div style="border:1px dashed #999; padding:10px; font-size:9px; color:#c00;">
                        {{ $errorLogo }}
                    </div>
                @endif
            </div>
            <div class="kop-teks">
                <p class="kop-yayasan">YAYASAN PONDOK PESANTREN AL - FATTAH KIKIL ARJOSARI<br>KABUPATEN PACITAN</p>
                <p class="kop-sekolah">SEKOLAH MENENGAH KEJURUAN PEMBANGUNAN</p>
                <p class="kop-jurusan">1. Tata Busana 2. Teknik Komputer & Jaringan 3. Rekayasa Perangkat Lunak<br>4.
                    Akuntansi dan Keuangan Lembaga</p>
                <p class="kop-npsn">NPSN : 20510982</p>
                <p class="kop-alamat">Jl. Nawangan Km. 01 Arjosari Pacitan. Telp./Fax (0357) 631008</p>
                <p class="kop-kontak">
                    Website : <span style="color: blue; text-decoration: underline;">http://smkpembangunanpacitan.sch.id</span>
                    Email : smkpembangunan_pct@yahoo.com
                </p>
            </div>
        </div>

        <hr class="garis-kop">

        <div class="judul-laporan">Laporan Rekapitulasi Presensi Kehadiran Siswa</div>

        <div class="info-tanggal">
            <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
            <strong>Filter:</strong> {{ ucfirst($filter) }}<br>
            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }}
            s/d {{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">NIS</th>
                    <th style="width: 25%;">Nama Siswa</th>
                    <th style="width: 12%;">Kelas</th>
                    <th style="width: 13%;">Tanggal</th>
                    <th style="width: 15%;">Jam Masuk</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['nis'] ?? '-' }}</td>
                        <td class="rata-kiri">{{ $item['nama_siswa'] ?? '-' }}</td>
                        <td>{{ $item['kelas'] ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d-M-Y') }}</td>
                        <td>{{ $item['jam_masuk'] ?? '-' }}</td>
                        <td>{{ $item['status'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #777; padding: 20px;">
                            Tidak ada data presensi yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="tanda-tangan">
            <div class="ttd-kanan">
                <p class="ttd-spasi">Pacitan, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Guru Bimbingan
                    Konseling (BK)</p>
                <p class="ttd-nama">Umi Kholifah, S.Pd</p>
                <p>NIP.-</p>
            </div>
        </div>

    </div>
</body>

</html>
