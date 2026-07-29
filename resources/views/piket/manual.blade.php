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

        .form-control-dark,
        .form-select-dark {
            background-color: #273142;
            border: 1px solid #334155;
            color: #ffffff;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control-dark:focus,
        .form-select-dark:focus {
            background-color: #2f3b4f;
            border-color: #00d2ff;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 210, 255, 0.25);
            outline: none;
            z-index: 5;
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

        /* --- KUSTOMISASI SELECT2 AGAR MENYATU DENGAN DARK MODE --- */

        /* 1. Kotak Utama Select2 */
        .select2-container--default .select2-selection--single {
            background-color: #273142 !important;
            border: 1px solid #334155 !important;
            border-radius: 8px !important;
            height: 50px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
            line-height: 48px !important;
            padding-left: 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
        }

        /* 2. Menu Dropdown ke Bawah */
        .select2-dropdown {
            background-color: #273142 !important;
            border: 1px solid #334155 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
            /* Efek melayang elegan */
            margin-top: 4px !important;
            overflow: hidden !important;
        }

        /* 3. Area Pembungkus Kotak Pencarian */
        .select2-search--dropdown {
            padding: 12px !important;
            background-color: #273142 !important;
        }

        /* 4. Kotak Ketik Pencariannya (Lebih Rapi dan Halus) */
        .select2-search__field {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
            /* Border halus, tidak putih mencolok */
            border-radius: 6px !important;
            padding: 10px 12px !important;
        }

        .select2-search__field:focus {
            border-color: #00d2ff !important;
            /* Warna biru saat diklik/mengetik */
            outline: none !important;
        }

        /* 5. Baris Pilihan Siswa */
        .select2-results__option {
            color: #e2e8f0 !important;
            padding: 10px 15px !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #00d2ff !important;
            color: #000000 !important;
            font-weight: bold !important;
        }
    </style>

    <!-- Panggil CSS Select2 dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

                <p class="text-secondary small text-center mb-4">Cari nama atau NIS siswa dan pilih keterangan kehadirannya.
                </p>

                <form id="formManual" action="{{ route('piket.simpan') }}" method="POST">
                    @csrf

                    <!-- PENCARIAN SISWA PINTAR DENGAN SELECT2 -->
                    <div class="mb-4">
                        <select name="nis" id="nis_search" class="form-control w-100" required>
                            <option value="">Ketik Nama atau NIS siswa...</option>
                            @foreach ($siswas as $s)
                                <option value="{{ $s->nis }}">{{ $s->nis }} - {{ $s->nama_siswa }} (Kelas:
                                    {{ $s->kelas }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- INPUT STATUS -->
                    <div class="input-group mb-4">
                        <span class="input-group-text input-group-text-dark px-4"
                            style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                            <i class="fas fa-check-square text-success fs-5"></i>
                        </span>
                        <select name="status" class="form-select form-select-dark"
                            style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" required>
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

<!-- SCRIPT AJAX & SELECT2 -->
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Panggil JS Select2 dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // 1. Inisialisasi Fitur Pencarian Select2
            $('#nis_search').select2({
                placeholder: "Ketik Nama atau NIS siswa...",
                allowClear: true,
                width: '100%' // Agar kotaknya penuh menyesuaikan form
            });

            // 2. Proses Kirim Data AJAX
            $('#formManual').on('submit', function(e) {
                e.preventDefault(); // Mencegah browser pindah ke halaman layar hitam

                let form = $(this);
                let btn = $('#btnSubmit');
                let originalBtnText = btn.html();

                // Ubah tombol jadi "Memproses..."
                btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...').prop('disabled', true);

                // Munculkan alert kuning
                $('#result-alert').removeClass('d-none alert-success alert-danger').addClass(
                    'alert-warning');
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
                            $('#pesan-hasil').html(
                                `✅ <strong>BERHASIL!</strong><br>Siswa: <strong>${response.nama}</strong><br>Jam: ${response.jam} | Status: <span class="badge bg-success">${response.status_kehadiran}</span>`
                                );

                            // Reset pilihan Select2 agar kosong kembali untuk input berikutnya
                            $('#nis_search').val(null).trigger('change');

                            // Kembalikan status ke default
                            form[0].reset();
                        } else {
                            // Tampilkan alert merah jika gagal (misal sudah absen)
                            $('#result-alert').addClass('alert-danger');
                            $('#pesan-hasil').html(
                                `❌ <strong>DITOLAK!</strong><br>${response.message}`);
                        }
                    },
                    error: function() {
                        btn.html(originalBtnText).prop('disabled', false);
                        $('#result-alert').removeClass('alert-warning').addClass(
                        'alert-danger');
                        $('#pesan-hasil').html(
                            "❌ <strong>ERROR!</strong> Terjadi kesalahan pada server. Coba lagi."
                            );
                    }
                });
            });
        });
    </script>
@endpush
