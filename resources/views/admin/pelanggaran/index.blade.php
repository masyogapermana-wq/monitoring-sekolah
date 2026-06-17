@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">⚠️ Kelola Jenis Pelanggaran</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('pelanggaran.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-bold">Nama Pelanggaran</label>
                    <input type="text" name="nama_pelanggaran" class="form-control" required placeholder="Contoh: Terlambat Masuk Sekolah">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">+ Tambah Aturan</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pelanggaran</th>

                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggarans as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_pelanggaran }}</td>

                        <td>
                            <form action="{{ route('pelanggaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aturan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
