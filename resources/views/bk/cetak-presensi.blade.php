<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Presensi - SMK Pembangunan</title>

    <style>
        /* 1. Pengaturan Font dan Kertas Dasar */
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        /* 2. Memaksa Ukuran Kertas A4 saat Dicetak */
        @page {
            size: A4;
            margin: 15mm;
        }

        .cetak-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* 3. Desain Kop Surat Resmi */
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

        .kop-jurusan, .kop-npsn, .kop-alamat, .kop-kontak {
            font-size: 12px;
        }

        /* Garis Ganda Pembatas Kop Surat */
        .garis-kop {
            border: none;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-bottom: 20px;
        }

        /* 4. Bagian Judul dan Info Laporan */
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

        /* 5. DESAIN TABEL AGAR RAPI BERGARIS */
        table {
            width: 100%;
            border-collapse: collapse; /* Menyatukan garis tepi tabel */
            margin-bottom: 30px;
        }

        table th, table td {
            border: 1px solid #000; /* Memberikan garis hitam pada tabel */
            padding: 8px 10px;
            text-align: center;
            font-size: 13px;
        }

        table th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
            margin-bottom: 70px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* 7. Pengaturan Khusus Print */
        @media print {
            .btn-cetak {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="cetak-container">

        <!-- Tombol untuk memicu print -->
        <div style="text-align: right; margin-bottom: 20px;" class="btn-cetak">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: #174b71; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                🖨️ Cetak Laporan Sekarang
            </button>
        </div>

        <!-- KOP SURAT YAYASAN AL-FATTAH -->
        <div class="kop-surat">
            <div class="kop-logo">
                <img src="{{ asset('images/logosekolah.jpg') }}" alt="File logo gagal dimuat" class="img-fluid" width="100">
            </div>
            <div class="kop-teks">
                <p class="kop-yayasan">YAYASAN PONDOK PESANTREN AL - FATTAH KIKIL ARJOSARI<br>KABUPATEN PACITAN</p>
                <p class="kop-sekolah">SEKOLAH MENENGAH KEJURUAN PEMBANGUNAN</p>
                <p class="kop-jurusan">1. Tata Busana 2. Teknik Komputer & Jaringan 3. Rekayasa Perangkat Lunak<br>4. Akuntansi dan Keuangan Lembaga</p>
                <p class="kop-npsn">NPSN : 20510982</p>
                <p class="kop-alamat">Jl. Nawangan Km. 01 Arjosari Pacitan. Telp./Fax (0357) 631008</p>
                <p class="kop-kontak">
                    Website : <span style="color: blue; text-decoration: underline;">http://smkpembangunanpacitan.sch.id</span>
                    Email : smkpembangunan_pct@yahoo.com
                </p>
            </div>
        </div>

        <!-- Garis Ganda -->
        <hr class="garis-kop">

        <!-- JUDUL LAPORAN PRESENSI -->
        <div class="judul-laporan">
            Laporan Rekapitulasi Presensi Kehadiran Siswa
        </div>

        <div class="info-tanggal">
            <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
        </div>

        <!-- TABEL DATA PRESENSI -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 15%;">Jam Masuk</th>
                    <th style="width: 10%;">NIS</th>
                    <th style="width: 40%;">Nama Siswa</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- LOOPING DATA PRESENSI -->
                <!-- Pastikan variabel $dataPresensi disesuaikan dengan yang kamu kirim dari Controller -->
                @forelse($dataPresensi ?? [] as $index => $presensi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('d-M-Y') }}</td>
                        <td>{{ $presensi->jam_masuk ?? '-' }}</td>
                        <td>{{ $presensi->siswa->nis ?? '-' }}</td>
                        <td class="rata-kiri">{{ $presensi->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $presensi->status ?? 'Hadir' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777; padding: 20px;">
                            Tidak ada data presensi yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- BAGIAN TANDA TANGAN -->
        <div class="tanda-tangan">
            <div class="ttd-kanan">
                <p>Pacitan, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>Guru Bimbingan Konseling (BK)</p>
                <p class="ttd-nama">Nama Guru BK, S.Pd.</p>
                <p>NIP. 19801234 200501 1 001</p>
            </div>
        </div>

    </div>

</body>
</html>
