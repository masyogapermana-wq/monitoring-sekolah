@extends('layouts.main')

@section('title', 'Input Pelanggaran Siswa')

@section('content')
    <!-- CSS KHUSUS HALAMAN INPUT PELANGGARAN -->
    <style>
        /* Desain Kotak Utama */
        .card-dark {
            background-color: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Desain Label */
        .form-label-custom {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        /* Desain Input, Select, & Textarea Gelap */
        .form-control-dark,
        .form-select-dark {
            background-color: #273142;
            border: 1px solid #334155;
            color: #ffffff;
            padding: 0.75rem 1rem;
            border-radius: 8px;
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
        }

        .form-control-dark::placeholder {
            color: #64748b;
        }

        /* Perbaikan icon kalender pada input date agar terlihat di dark mode (Khusus Webkit) */
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.6;
            cursor: pointer;
        }

        /* Tombol Cek Biru */
        .btn-cek {
            background-color: #3b82f6;
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-cek:hover {
            background-color: #2563eb;
            color: white;
        }

        /* Tombol Simpan Merah Glowing */
        .btn-glow-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
            transition: all 0.3s ease;
        }

        .btn-glow-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(239, 68, 68, 0.6);
            color: #ffffff;
        }

        /* Kotak Instruksi */
        .instruction-card {
            background-color: rgba(0, 210, 255, 0.05);
            border: 1px solid rgba(0, 210, 255, 0.2);
            border-radius: 16px;
            color: #cbd5e1;
        }

        .instruction-card ol li {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
    </style>

    <div class="container-fluid">

        <!-- Header -->
        <div class="mb-4 d-flex align-items-center">
            <i class="fas fa-file-signature fa-2x me-3 text-warning"></i>
            <h3 class="fw-bold text-white mb-0">Input Pelanggaran Siswa</h3>
        </div>

        <div class="row g-4">

            <!-- KOLOM KIRI: FORM INPUT -->
            <div class="col-lg-7">
                <div class="card-dark p-4">

                    <form action="{{ route('bk.store-pelanggaran') }}" method="POST">
                        @csrf

                        <!-- Tanggal Kejadian -->
                        <div class="mb-4">
                            <label class="form-label-custom">Tanggal Kejadian</label>
                            <!-- Value default diisi tanggal hari ini menggunakan PHP -->
                            <input type="date" name="tanggal" class="form-control form-control-dark"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- NIS Siswa -->
                        <div class="mb-4">
                            <label class="form-label-custom">NIS Siswa</label>
                            <div class="input-group">
                                <input type="text" name="nis" id="nisInput" class="form-control form-control-dark"
                                    placeholder="Scan QR / Ketik NIS" required>
                                <button class="btn btn-cek px-4" type="button" id="btnCekNis">
                                    <i class="fas fa-search me-1"></i> Cek
                                </button>
                            </div>
                            <!-- Tempat munculnya nama siswa jika NIS ditemukan (Bisa diolah pakai JS nanti) -->
                            <div id="namaSiswaResult" class="mt-2 small text-info fw-bold d-none"></div>
                        </div>

                        <!-- Jenis Pelanggaran (SEKARANG OTOMATIS DARI DATABASE ADMIN) -->
                        <div class="mb-4">
                            <label class="form-label-custom">Jenis Pelanggaran</label>
                            <select name="jenis_pelanggaran_id" class="form-select form-select-dark" required>
                                <option value="" selected disabled>-- Pilih Pelanggaran --</option>

                                <!-- Perintah ini akan melooping data asli dari tabel jenis_pelanggaran -->
                                @foreach ($jenisPelanggaran as $pelanggaran)
                                    <option value="{{ $pelanggaran->id }}">{{ $pelanggaran->nama_pelanggaran }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Sanksi Edukatif yang Diberikan</label>
                            <select name="sanksi" class="form-select form-select-dark" required>
                                <option value="" selected disabled>-- Pilih Sanksi Edukatif --</option>

                                <!-- Looping data otomatis dari database -->
                                @foreach ($sanksiEdukatifs as $sanksi)
                                    <option value="{{ $sanksi->nama_sanksi }}">{{ $sanksi->nama_sanksi }}</option>
                                @endforeach

                            </select>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mb-4">
                            <label class="form-label-custom">Catatan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control form-control-dark" rows="3"
                                placeholder="Kronologi singkat atau detail sanksi..."></textarea>
                        </div>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn w-100 btn-glow-danger mt-2">
                            <i class="fas fa-exclamation-triangle me-2"></i> SIMPAN PELANGGARAN
                        </button>
                    </form>

                </div>
            </div>

            <!-- KOLOM KANAN: INSTRUKSI -->
            <div class="col-lg-5">
                <div class="instruction-card p-4">
                    <h5 class="fw-bold mb-3" style="color: #00d2ff;">
                        <i class="fas fa-info-circle me-2"></i> Cara Penggunaan:
                    </h5>
                    <ol class="ps-3 mb-0">
                        <li>Pastikan kursor aktif di kolom <strong>NIS</strong>.</li>
                        <li>Scan Kartu Pelajar pakai Scanner (atau ketik manual lalu klik Cek).</li>
                        <li>Pastikan nama siswa muncul dan sesuai dengan kartu.</li>
                        <li>Pilih jenis pelanggaran dari daftar.</li>
                        <li>Pilih sanksi edukatif yang diberikan kepada siswa.</li>
                        <li>Klik Simpan. Data pelanggaran, sanksi, dan poin akan bertambah otomatis.</li>
                    </ol>
                </div>

                <!-- Tombol Kembali (Opsional, agar rapi) -->
                <div class="text-center mt-4">
                    <a href="{{ url('/piket/dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4"
                        style="border-color: #334155; color: #94a3b8;">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
