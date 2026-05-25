@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-4 text-center">📷 Scan QR Presensi</h3>

                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-4">

                        <div id="result-alert" class="alert d-none mb-4 shadow-sm" role="alert" style="font-size: 1.2rem;">
                            <span id="nis-hasil" class="mb-0"></span>
                        </div>

                        <div id="reader"
                            style="width: 100%; max-width: 500px; margin: 0 auto; border-radius: 10px; overflow: hidden;">
                        </div>

                        <p class="text-muted mt-3">Arahkan kamera ke QR Code Kartu Pelajar siswa.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            // Kalau masih memproses scan sebelumnya, abaikan scan baru
            if (isProcessing) return;
            isProcessing = true;

            // Mainkan suara 'beep'
            let audio = new Audio('https://www.soundjay.com/button/beep-07.wav');
            audio.play().catch(e => console.log('Autoplay audio ditahan browser.'));

            // Munculkan notifikasi "Sedang Memproses" di ATAS kamera
            $('#result-alert').removeClass('d-none alert-success alert-danger').addClass('alert-warning');
            $('#nis-hasil').html('⏳ <strong>Memproses...</strong> Mohon tunggu.');

            // Kirim Data ke Server (AJAX)
            $.ajax({
                url: "{{ route('piket.simpan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nis: decodedText
                },
                success: function(response) {
                    $('#result-alert').removeClass('alert-warning');

                    if (response.status == 'success') {
                        // TAMPILAN JIKA BERHASIL (WARNA HIJAU)
                        $('#result-alert').addClass('alert-success');
                        $('#nis-hasil').html(`
                        <h4 class="fw-bold mb-1">✅ BERHASIL!</h4>
                        <strong>${response.nama}</strong><br>
                        Jam: ${response.jam} | Status: <span class="badge bg-success">${response.status_kehadiran}</span>
                    `);
                    } else {
                        // TAMPILAN JIKA GAGAL / SUDAH ABSEN (WARNA MERAH)
                        $('#result-alert').addClass('alert-danger');
                        $('#nis-hasil').html(`<h4 class="fw-bold mb-1">❌ DITOLAK!</h4>${response.message}`);
                    }

                    // JEDA DITAMBAH JADI 5 DETIK biar layar gak cepat hilang dan gak dobel scan
                    setTimeout(() => {
                        $('#result-alert').addClass('d-none'); // Sembunyikan notifikasi
                        isProcessing = false; // Buka kunci biar bisa scan siswa berikutnya
                    }, 5000);
                },
                error: function() {
                    $('#result-alert').removeClass('alert-warning').addClass('alert-danger');
                    $('#nis-hasil').html(
                        "<h4 class='fw-bold mb-1'>❌ ERROR!</h4> Terjadi kesalahan sistem. Coba lagi.");
                    setTimeout(() => {
                        $('#result-alert').addClass('d-none');
                        isProcessing = false;
                    }, 5000);
                }
            });
        }

        // KONFIGURASI SCANNER
        // KONFIGURASI SCANNER (VERSI KAMERA BELAKANG)
        let config = {
            fps: 30,
            qrbox: {
                width: 250,
                height: 250
            },
            rememberLastUsedCamera: true, // Ingat pilihan terakhir
            formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
            // 🔥 INI KUNCINYA BRO: Paksa pilih kamera yang menghadap ke luar
            videoConstraints: {
                facingMode: "environment"
            }
        };

        // Render scanner seperti biasa
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
