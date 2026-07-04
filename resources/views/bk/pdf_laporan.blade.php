<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelanggaran - SMK Pembangunan</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; color: #000; }
        .kop-surat { width: 100%; border-bottom: 3px double #000; padding-bottom: 5px; margin-bottom: 15px; }
        .kop-surat td { text-align: center; }
        .logo { width: 80px; }
        .teks-kop h4, .teks-kop h3 { margin: 0; font-weight: bold; }
        .teks-kop h3 { font-size: 16px; }
        .teks-kop h4 { font-size: 14px; }
        .teks-kop p { margin: 2px 0 0 0; font-size: 10px; }

        .judul-laporan { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 15px; font-size: 14px; }
        .info-tanggal { font-weight: bold; margin-bottom: 5px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; }
        table.data th { text-align: center; font-weight: bold; font-size: 11px; background-color: #f2f2f2; }
        .text-center { text-align: center; }

        .ttd-container { width: 250px; float: right; text-align: center; margin-top: 20px; }
        .nama-ttd { font-weight: bold; text-decoration: underline; margin-top: 60px; }
    </style>
</head>
<body>

    <!-- KOP SURAT (Sama dengan Laporan Presensi) -->
    <table class="kop-surat">
        <tr>
            <td width="15%">
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
                    <img src="{{ $base64 }}" class="logo" alt="Logo SMK Pembangunan">
                @else
                    <span style="font-size: 10px; color: red;">File logo gagal dimuat</span>
                @endif
            </td>
            <td width="85%" class="teks-kop">
                <h4>YAYASAN PONDOK PESANTREN AL - FATTAH KIKIL ARJOSARI</h4>
                <h4>KABUPATEN PACITAN</h4>
                <h3>SEKOLAH MENENGAH KEJURUAN PEMBANGUNAN</h3>
                <p>1. Tata Busana 2. Teknik Komputer & Jaringan 3. Rekayasa Perangkat Lunak 4. Akuntansi dan Keuangan Lembaga</p>
                <p>NPSN : 20510982</p>
                <p>Jl. Nawangan Km. 01 Arjosari Pacitan. Telp./Fax (0357) 631008</p>
                <p>Website : http://smkpembangunanpacitan.sch.id Email : smkpembangunan_pct@yahoo.com</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="judul-laporan">LAPORAN REKAPITULASI PELANGGARAN SISWA</div>

    <!-- INFO FILTER -->
    <div class="info-tanggal">
        Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
        Filter: {{ ucfirst($filter) }}
    </div>

    <!-- TABEL DATA PELANGGARAN -->
    <table class="data">
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

    <!-- TANDA TANGAN -->
    <div class="ttd-container">
        <p>Pacitan, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}<br>Guru Bimbingan Konseling (BK)</p>
        <div class="nama-ttd">Nama Guru BK, S.Pd.</div>
        <p style="margin-top: 2px;">NIP. 19801234 200501 1 001</p>
    </div>

</body>
</html>
