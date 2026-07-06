<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggaran - SMK Pembangunan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }

        /* =========================================
           1. CSS KOP SURAT (Desain Bersih)
           ========================================= */
        .kop-surat { width: 100%; border-collapse: collapse; border: none; border-bottom: 2px solid #000; margin-bottom: 20px; }
        .kop-surat td { border: none; padding: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h3 { margin: 0; padding: 0; font-size: 16px; }
        .kop-teks p { margin: 2px 0; font-size: 11px; }

        /* =========================================
           2. CSS JUDUL & INFO
           ========================================= */
        .judul-laporan { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 15px; text-decoration: underline; }
        .info-tanggal { margin-bottom: 15px; font-weight: bold; }

        /* =========================================
           3. CSS TABEL DATA (Desain Rapi)
           ========================================= */
        .tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        .tabel-data th, .tabel-data td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .tabel-data th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }

        /* =========================================
           4. CSS TANDA TANGAN (Dikembalikan dari kodemu)
           ========================================= */
        .ttd-container { width: 250px; float: right; text-align: center; margin-top: 20px; }
        .nama-ttd { font-weight: bold; text-decoration: underline; margin-top: 60px; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="kop-surat">
        <tr>
            <!-- Kolom Logo -->
            <td style="width: 15%; text-align: center;">
                <!-- Logika Base64 Image (Dipertahankan agar anti-error di DomPDF) -->
                @php
                    $path = public_path('images/logo-smk.png.jpg');
                    if(file_exists($path)) {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $base64 = '';
                    }
                @endphp

                @if($base64)
                    <img src="{{ $base64 }}" alt="Logo SMK Pembangunan" style="width: 75px; height: auto;">
                @else
                    <span style="font-size: 10px; color: red;">File logo gagal dimuat</span>
                @endif
            </td>

            <!-- Kolom Teks -->
            <td style="width: 70%;" class="kop-teks">
                <h3>SEKOLAH MENENGAH KEJURUAN PEMBANGUNAN</h3>
                <p>1. Tata Busana 2. Teknik Komputer & Jaringan 3. Rekayasa Perangkat Lunak 4. Akuntansi</p>
                <p>NPSN : 20510982</p>
                <p>Jl. Nawangan Km. 01 Arjosari Pacitan. Telp./Fax (0357) 631008</p>
                <p>Website: http://smkpembangunanpacitan.sch.id &nbsp; Email: smkpembangunan_pct@yahoo.com</p>
            </td>

            <!-- Kolom Penyeimbang -->
            <td style="width: 15%;"></td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="judul-laporan">LAPORAN REKAPITULASI PELANGGARAN SISWA</div>

    <div class="info-tanggal">
        Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
        Filter: {{ ucfirst($filter ?? 'Harian') }}
    </div>

    <!-- TABEL DATA -->
    <table class="tabel-data">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="12%">TANGGAL</th>
                <th width="15%">NIS</th>
                <th width="25%">NAMA SISWA</th>
                <th width="23%">JENIS PELANGGARAN</th>
                <th width="20%">SANKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggarans as $index => $pelanggaran)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($pelanggaran->tanggal_kejadian)->translatedFormat('d/m/Y') }}</td>
                <td class="text-center">{{ $pelanggaran->siswa->nis ?? '-' }}</td>
                <td>{{ $pelanggaran->siswa->nama_siswa ?? '-' }}</td>
                <td>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                <td>{{ $pelanggaran->sanksi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data pelanggaran siswa yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- AREA TANDA TANGAN (Dipertahankan dari kodemu) -->
    <div class="ttd-container">
        <p>Pacitan, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}<br>Guru Bimbingan Konseling (BK)</p>
        <div class="nama-ttd">Nama Guru BK, S.Pd.</div>
        <p style="margin-top: 2px;">NIP. 19801234 200501 1 001</p>
    </div>

</body>
</html>
