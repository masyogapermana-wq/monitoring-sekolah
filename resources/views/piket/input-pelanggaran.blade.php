@extends('layouts.main')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">📝 Input Pelanggaran Siswa</h3>

    <!-- Pesan Sukses Jika Data Berhasil Disimpan -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <strong>❌ Gagal Menyimpan Data!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Kiri: Form Input Pelanggaran -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('piket.store-pelanggaran') }}" method="POST">
                        @csrf


                        <input type="hidden" name="siswa_id" id="siswa_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        

                        <!-- Input NIS (Bisa Scan / Ketik Manual) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIS Siswa</label>
                            <div class="input-group">
                                <input type="text" id="nis" name="nis" class="form-control" placeholder="Scan QR / Ketik NIS" required autofocus>
                                <button type="button" class="btn btn-secondary" onclick="cekSiswa()">🔍 Cek</button>
                            </div>
                            <!-- Tempat muncul nama siswa secara otomatis -->
                            <small id="nama_siswa_info" class="text-primary fw-bold mt-2 d-block"></small>
                        </div>

                        <!-- Pilih Jenis Pelanggaran -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Pelanggaran</label>
                            <select name="jenis_pelanggaran_id" class="form-select" required>
                                <option value="">-- Pilih Pelanggaran --</option>
                                @foreach($jenisPelanggaran as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_pelanggaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Sanksi Edukatif (Revisi Baru) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sanksi Edukatif yang Diberikan</label>
                            <select name="sanksi" class="form-select" required>
                                <option value="">-- Pilih Sanksi Edukatif --</option>
                                <option value="Membaca Al-Qur'an">Membaca Al-Qur'an</option>
                                <option value="Membersihkan Area Sekolah">Membersihkan Area Sekolah</option>
                                <option value="Menghafal Surat Pendek">Menghafal Surat Pendek</option>
                                <option value="Lainnya">Lainnya (Tulis di Catatan)</option>
                            </select>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Kronologi singkat atau detail sanksi..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">⚠️ SIMPAN PELANGGARAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Panduan Penggunaan -->
        <div class="col-md-6">
            <div class="alert alert-info">
                <h5>ℹ️ Cara Penggunaan:</h5>
                <ol>
                    <li>Pastikan kursor aktif di kolom <strong>NIS</strong>.</li>
                    <li>Scan Kartu Pelajar pakai Scanner (atau ketik manual lalu klik Cek).</li>
                    <li>Pastikan nama siswa muncul dan sesuai dengan kartu.</li>
                    <li>Pilih jenis pelanggaran dari daftar.</li>
                    <li>Pilih sanksi edukatif yang diberikan kepada siswa.</li>
                    <li>Klik Simpan. Data pelanggaran, sanksi, dan poin akan bertambah otomatis.</li>
                </ol>
            </div>
            <div class="text-center mt-4">
                <a href="/piket/dashboard" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>

<!-- Script Javascript untuk Mengecek Nama Siswa Otomatis -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fungsi cek nama siswa saat tombol diklik atau saat tekan tombol Enter
    function cekSiswa() {
        let nis = $('#nis').val();
        if(nis == '') return;

        $.ajax({
            url: "{{ route('piket.cek-siswa') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                nis: nis
            },
            success: function(response) {
                if(response.status == 'success') {
                    $('#nama_siswa_info').html(`✅ Siswa Ditemukan: ${response.data.nama_siswa} (Kelas ${response.data.kelas})`);
                    $('#nis').addClass('is-valid').removeClass('is-invalid');

                    // 🔥 TAMBAHAN BARU: Masukkan ID Siswa ke dalam input tersembunyi
                    $('#siswa_id').val(response.data.id);
                } else {
                    $('#nama_siswa_info').html(`<span class="text-danger">❌ Siswa tidak ditemukan!</span>`);
                    $('#nis').addClass('is-invalid').removeClass('is-valid');

                    // 🔥 TAMBAHAN BARU: Kosongkan ID jika siswa tidak ada
                    $('#siswa_id').val('');
                }
            }
        });
    }

    // Biar pas tekan Enter di kolom NIS, dia nge-cek nama dulu, bukan langsung submit form
    $('#nis').on('keypress', function(e) {
        if(e.which == 13) {
            e.preventDefault(); // Mencegah form langsung tersubmit
            cekSiswa();
        }
    });
</script>
@endsection
