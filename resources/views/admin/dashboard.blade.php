@extends('layouts.main')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title">Selamat Datang, Admin!</h4>
            <p class="card-text">Ini adalah halaman utama untuk mengelola data master sekolah.</p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3>{{ $totalSiswa }}</h3>
                            <p>Total Siswa</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h3>{{ $hadirHariIni }}</h3>
                            <p>Siswa Hadir Hari Ini</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h3>{{ $pelanggaranHariIni }}</h3>
                            <p>Pelanggaran Hari Ini</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
