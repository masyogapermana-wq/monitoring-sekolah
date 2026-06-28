@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mt-4">
            <h3 class="fw-bold mb-4"> Pengaturan Sistem</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Batas Jam Masuk (Keterlambatan)</label>
                            <input type="time" name="jam_masuk" class="form-control form-control-lg" value="{{ $pengaturan->jam_masuk }}" required>
                            <small class="text-muted">Siswa yang melakukan presensi melewati jam ini akan otomatis berstatus Terlambat.</small>
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold">💾 Simpan Peraturan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
