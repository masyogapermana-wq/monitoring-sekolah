@extends('layouts.main')

@section('title', 'Presensi Manual')

@section('content')
<!-- CSS KHUSUS HALAMAN PRESENSI MANUAL -->
<style>
    .card-dark {
        background-color: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .form-control-dark, .form-select-dark {
        background-color: #273142;
        border: 1px solid #334155;
        color: #ffffff;
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        background-color: #2f3b4f;
        border-color: #00d2ff;
        color: #ffffff;
        box-shadow: 0 0 0 3px rgba(0, 210, 255, 0.25);
        outline: none;
        z-index: 5;
    }

    .form-control-dark::placeholder {
        color: #64748b;
    }

    .input-group-text-dark {
        background-color: #334155;
        border: 1px solid #334155;
        color: #e2e8f0;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .btn-glow-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);
        transition: all 0.3s ease;
    }

    .btn-glow-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(34, 197, 94, 0.6);
        color: #ffffff;
    }
</style>

<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 65vh;">
    <div class="col-md-8 col-lg-6 w-100" style="max-width: 650px;">

        <h4 class="text-center text-white fw-bold mb-4">
            <i class="fas fa-keyboard retro-icon me-2" style="color: #a5b1c2;"></i> Presensi Manual
        </h4>

        <div class="card-dark p-4 p-md-5">

            <!-- TEMPAT KOTAK NOTIFIKASI MUNCUL -->
            <div id="result-alert" class="alert d-none mb-4 shadow-sm" role="alert" style="border-radius: 12px;">
                <span id="pesan-hasil"></span>
            </div>

            <p class="text-secondary small text-center mb-4">Masukkan NIS siswa dan pilih keterangan kehadirannya.</p>

            <!-- Tambahkan ID 'formManual' pada form ini -->
            <form id="formManual" action="{{ route('piket.simpan') }}" method="POST">
                @csrf

                <div class="input-group mb-4">
                    <span class="input-group-text input-group-text-dark px-4" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                        NIS
                    </span>
                    <input type="number" name="nis" class="form-control form-control-dark" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" placeholder="Ketik NIS di sini..." required>
                </div>

                <div class="input-group mb-4">
                    <span class="input-group-text input-group-text-dark px-4" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                        <i class="fas fa-check-square text-success fs-5"></i>
                    </span>
                    <select name="status" class="form-select form-select-dark" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" required>
                        <option value="Hadir" selected>Hadir (Lupa Bawa Kartu)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alpha">Alpha</option>
                    </select>
                </div>

                <button type="submit" id="btnSubmit" class="btn w-100 btn-glow-success mt-2">
                    <i class="fas fa-save me-2"></i> Simpan Presensi
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

<!-- SCRIPT AJAX UNTUK MENCEGAT FORM AGAR TIDAK PINDAH HALAMAN -->
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#formManual').on('submit', function(e) {
            e.preventDefault(); // Mencegah browser pindah ke halaman layar hitam

            let form = $(this);
            let btn = $('#btnSubmit');
            let originalBtnText = btn.html();

            // Ubah tombol jadi "Memproses..." agar tidak di-klik dua kali
            btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...').prop('disabled', true);

            // Munculkan alert kuning
            $('#result-alert').removeClass('d-none alert-success alert-danger').addClass('alert-warning');
            $('#pesan-hasil').html('⏳ <strong>Menyimpan data...</strong> Mohon tunggu.');

            // Kirim data ke belakang layar menggunakan AJAX
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // Kembalikan tombol seperti semula
                    btn.html(originalBtnText).prop('disabled', false);
                    $('#result-alert').removeClass('alert-warning');

                    if (response.status === 'success') {
                        // Tampilkan alert hijau jika sukses
                        $('#result-alert').addClass('alert-success');
                        $('#pesan-hasil').html(`✅ <strong>BERHASIL!</strong><br>Siswa: <strong>${response.nama}</strong><br>Jam: ${response.jam} | Status: <span class="badge bg-success">${response.status_kehadiran}</span>`);

                        // Kosongkan form NIS agar siap dipakai untuk siswa berikutnya
                        form[0].reset();
                    } else {
                        // Tampilkan alert merah jika gagal (misal sudah absen)
                        $('#result-alert').addClass('alert-danger');
                        $('#pesan-hasil').html(`❌ <strong>DITOLAK!</strong><br>${response.message}`);
                    }
                },
                error: function() {
                    btn.html(originalBtnText).prop('disabled', false);
                    $('#result-alert').removeClass('alert-warning').addClass('alert-danger');
                    $('#pesan-hasil').html("❌ <strong>ERROR!</strong> Terjadi kesalahan pada server. Coba lagi.");
                }
            });
        });
    });
</script>
@endpush
