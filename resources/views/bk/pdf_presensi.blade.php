<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }

        /* CSS Khusus untuk Kop Surat */
        .kop-surat { width: 100%; border-collapse: collapse; border: none; border-bottom: 2px solid #000; margin-bottom: 20px; }
        .kop-surat td { border: none; padding: 5px; }
        .kop-teks { text-align: center; }
        .kop-teks h3 { margin: 0; padding: 0; font-size: 16px; }
        .kop-teks p { margin: 2px 0; font-size: 11px; }

        .judul-laporan { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 15px; }
        .info-tanggal { margin-bottom: 15px; }

        /* CSS Khusus untuk Tabel Data */
        .tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabel-data th, .tabel-data td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .tabel-data th { background-color: #f2f2f2; text-align: center; }

        .text-center { text-align: center; }
    </style>
</head>
<body>

    <!-- KOP SURAT SMK PEMBANGUNAN -->
    <table class="kop-surat">
        <tr>
            <!-- Kolom Logo -->
            <td style="width: 15%; text-align: center;">
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
                    <img src="{{ $base64 }}" alt="Logo SMK" style="width: 75px; height: auto;">
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

            <!-- Kolom Kosong Penyeimbang -->
            <td style="width: 15%;"></td>
        </tr>
    </table>

    <!-- JUDUL DAN INFO -->
    <div class="judul-laporan">LAPORAN REKAPITULASI PRESENSI SISWA</div>

    <div class="info-tanggal">
        Tanggal Cetak : {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }} <br>
        Tanggal Data : {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }} <br>
        Filter : {{ ucfirst($filter ?? 'Harian') }}
    </div>

    <!-- TABEL DATA PRESENSI -->
    <table class="tabel-data">
        <thead>
            <tr>
                <!-- Persentase lebar disesuaikan agar pas 100% -->
                <th width="5%">NO</th>
                <th width="12%">TANGGAL</th>
                <th width="15%">NIS</th>
                <th width="30%">NAMA SISWA</th>
                <th width="14%">KELAS</th>
                <th width="12%">JAM MASUK</th>
                <th width="12%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $absen)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <!-- Memformat variabel $tanggal menjadi d-m-Y -->
                <td class="text-center">{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</td>
                <td class="text-center">{{ $absen['nis'] }}</td>
                <td>{{ $absen['nama_siswa'] }}</td>
                <td class="text-center">{{ $absen['kelas'] }}</td>
                <td class="text-center">{{ $absen['jam_masuk'] }}</td>
                <td class="text-center">{{ $absen['status'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data presensi pada tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
