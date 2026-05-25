@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Selamat Datang, Guru Piket 👋</h2>
            <p class="text-muted">Pilih menu di bawah ini untuk mengelola presensi kedatangan dan pelanggaran tata tertib siswa hari ini.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-5">
                    <div class="display-3 text-primary mb-3">📷</div>
                    <h4 class="fw-bold">Scan QR Presensi</h4>
                    <p class="text-muted">Catat kehadiran otomatis menggunakan kamera web / barcode scanner.</p>
                    <a href="{{ route('piket.scan') }}" class="btn btn-primary mt-3 btn-lg px-5">Buka Scanner</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-5">
                    <div class="display-3 text-success mb-3">⌨️</div>
                    <h4 class="fw-bold">Presensi Manual</h4>
                    <p class="text-muted">Catat kehadiran siswa yang lupa membawa kartu dengan mengetik NIS.</p>
                    <a href="{{ route('piket.manual') }}" class="btn btn-success mt-3 btn-lg px-5">Input Manual</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
