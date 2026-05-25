<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Pelanggaran - SMK Pembangunan</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }
        .header h2, .header h4 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        table th, table td { border: 1px solid #000; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .footer-ttd { width: 100%; margin-top: 50px; }
        .footer-ttd td { border: none; text-align: center; width: 50%; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>SMK PEMBANGUNAN</h2>
        <h4>LAPORAN PELANGGARAN & SANKSI EDUKATIF SISWA</h4>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Kejadian</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th>Sanksi Edukatif</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggarans as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d-m-Y') }}</td>
                <td class="text-center">{{ $item->siswa->nis ?? '-' }}</td>
                <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                <td class="text-center">{{ $item->siswa->kelas ?? '-' }}</td>
                <td>{{ $item->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                <td>{{ $item->sanksi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-ttd">
        <tr>
            <td>
                <br>Mengetahui,<br>Kepala Sekolah
                <br><br><br><br>
                <strong>( .................................... )</strong>
            </td>
            <td>
                Pacitan, {{ date('d F Y') }}<br>Guru Bimbingan Konseling
                <br><br><br><br>
                <strong>( .................................... )</strong>
            </td>
        </tr>
    </table>

</body>
</html>
