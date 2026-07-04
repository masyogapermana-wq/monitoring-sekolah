@extends('layouts.main')

@section('title', 'Scan QR Presensi')

@section('content')

<!-- CSS KHUSUS UNTUK MEMPERBAIKI TAMPILAN KAMERA -->
<style>
    /* Membuat video kamera penuh menyesuaikan kotak pembungkusnya */
    #reader video {
        width: 100% !important;
        border-radius: 12px !important;
        object-fit: cover; /* Biar gambar gak gepeng */
    }

    /* Menyembunyikan tombol "Stop Scanning" bawaan library yang jelek */
    #html5-qrcode-button-camera-stop,
    #html5-qrcode-button-camera-start {
        display: none !important;
    }

    /* Merapikan link "Request Camera Permissions" dari library */
    #reader__dashboard_section_csr span {
        color: #94a3b8 !important;
        font-size: 0.8rem;
    }
</style>

<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-8 col-lg-6 w-100" style="max-width: 600px;">

        <!-- Judul Halaman -->
        <h4 class="text-center text-white fw-bold mb-4">
            <i class="fas fa-camera retro-icon me-2" style="color: #00d2ff;"></i> Scan QR Presensi
        </h4>

        <!-- Kotak Scanner Dark Mode -->
        <div class="card p-4 text-center" style="background-color: #1e293b; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">

            <!-- KOTAK NOTIFIKASI -->
            <div id="result-alert" class="alert d-none mb-4 shadow-sm" role="alert" style="border-radius: 12px; transition: all 0.3s ease;">
                <span id="nis-hasil"></span>
            </div>

            <!-- Tempat Kamera / Scanner -->
            <div id="reader" class="mx-auto overflow-hidden mb-4" style="border-radius: 16px; border: 2px solid rgba(0, 210, 255, 0.2); background-color: #0b1320; min-height: 250px; width: 100%;">
                <!-- Kamera dari JavaScript akan otomatis muncul di dalam sini -->
            </div>

            <!-- Teks Petunjuk -->
            <p class="text-secondary small mb-4">
                <i class="fas fa-info-circle me-1"></i> Arahkan kamera ke QR Code Kartu Pelajar siswa.
            </p>

            <!-- Tombol Aksi (Sudah Diberi Fungsi JS) -->
            <div>
                <button type="button" id="stopScanBtn" class="btn text-white fw-bold px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%); border: none; box-shadow: 0 4px 15px rgba(255, 71, 87, 0.3);">
                    <i class="fas fa-stop-circle me-1"></i> Stop Scanning
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let isProcessing = false;
        let html5QrcodeScanner; // Variabel dipindah ke atas agar bisa diakses tombol merah

        function onScanSuccess(decodedText, decodedResult) {
            // Kalau masih memproses scan sebelumnya, abaikan scan baru
            if (isProcessing) return;
            isProcessing = true;

            // Mainkan suara 'beep'
            let audio = new Audio('https://www.soundjay.com/button/beep-07.wav');
            audio.play().catch(e => console.log('Autoplay audio ditahan browser.'));

            // Munculkan notifikasi "Sedang Memproses"
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
                        $('#result-alert').addClass('alert-success');
                        $('#nis-hasil').html(`
                        <h4 class="fw-bold mb-1">✅ BERHASIL!</h4>
                        <strong>${response.nama}</strong><br>
                        Jam: ${response.jam} | Status: <span class="badge bg-success">${response.status_kehadiran}</span>
                    `);
                    } else {
                        $('#result-alert').addClass('alert-danger');
                        $('#nis-hasil').html(`<h4 class="fw-bold mb-1">❌ DITOLAK!</h4>${response.message}`);
                    }

                    setTimeout(() => {
                        $('#result-alert').addClass('d-none');
                        isProcessing = false;
                    }, 5000);
                },
                error: function() {
                    $('#result-alert').removeClass('alert-warning').addClass('alert-danger');
                    $('#nis-hasil').html("<h4 class='fw-bold mb-1'>❌ ERROR!</h4> Terjadi kesalahan sistem. Coba lagi.");

                    setTimeout(() => {
                        $('#result-alert').addClass('d-none');
                        isProcessing = false;
                    }, 5000);
                }
            });
        }

        // KONFIGURASI SCANNER
        let config = {
            fps: 30,
            // qrbox sengaja dihapus agar area scan memenuhi seluruh kotak kamera
            rememberLastUsedCamera: true,
            formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
            videoConstraints: {
                facingMode: "environment"
            }
        };

        // Render scanner
        html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
        html5QrcodeScanner.render(onScanSuccess);

        // ==========================================
        // FUNGSI UNTUK TOMBOL MERAH KITA
        // ==========================================
        document.getElementById('stopScanBtn').addEventListener('click', function() {
            if (html5QrcodeScanner) {
                // Perintah untuk mematikan kamera
                html5QrcodeScanner.clear().then(() => {
                    // Kamera berhasil mati, arahkan user kembali ke Dashboard
                    window.location.href = "/piket/dashboard";
                }).catch(error => {
                    console.error("Gagal mematikan scanner.", error);
                });
            }
        });
    </script>
@endpush
