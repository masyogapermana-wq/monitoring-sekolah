@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <h3 class="fw-bold mb-4 text-center">⌨️ Presensi Manual</h3>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">

                    <div id="result-alert" class="alert d-none mb-4 shadow-sm" role="alert">
                        <span id="nis-hasil" class="mb-0"></span>
                    </div>

                    <p class="text-muted mb-3">Masukkan NIS siswa yang lupa membawa kartu QR.</p>

                    <div class="input-group input-group-lg shadow-sm mb-3">
                        <span class="input-group-text bg-light fw-bold">NIS</span>
                        <input type="number" id="manual-nis" class="form-control" placeholder="Ketik NIS di sini..." autocomplete="off" autofocus>
                        <button class="btn btn-success px-4 fw-bold" type="button" id="btn-manual">Catat Hadir</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let isProcessing = false;

    $('#btn-manual').click(function() {
        let nisManual = $('#manual-nis').val();
        if(nisManual.trim() === '') { alert('Tolong masukkan NIS siswa!'); return; }
        if (isProcessing) return;
        isProcessing = true;

        // Suara beep penanda sukses/proses
        let audio = new Audio('https://www.soundjay.com/button/beep-07.wav');
        audio.play().catch(e => console.log('Autoplay ditahan browser.'));

        $('#result-alert').removeClass('d-none alert-success alert-danger').addClass('alert-warning');
        $('#nis-hasil').html('⏳ <strong>Memproses...</strong>');

        $.ajax({
            url: "{{ route('piket.simpan') }}", // Jalur simpan ke database
            type: "POST",
            data: { _token: "{{ csrf_token() }}", nis: nisManual },
            success: function(response) {
                $('#result-alert').removeClass('alert-warning');
                if(response.status == 'success') {
                    $('#result-alert').addClass('alert-success');
                    $('#nis-hasil').html(`✅ <strong>BERHASIL!</strong><br>${response.nama} - <span class="badge bg-success">${response.status_kehadiran}</span>`);
                } else {
                    $('#result-alert').addClass('alert-danger');
                    $('#nis-hasil').html(`❌ <strong>DITOLAK!</strong><br>${response.message}`);
                }

                $('#manual-nis').val('').focus(); // Fokuskan lagi ke inputan biar bisa langsung ngetik siswa berikutnya

                setTimeout(() => {
                    $('#result-alert').addClass('d-none');
                    isProcessing = false;
                }, 4000);
            },
            error: function() {
                $('#result-alert').removeClass('alert-warning').addClass('alert-danger');
                $('#nis-hasil').html("❌ <strong>ERROR!</strong> Sistem bermasalah atau rute simpan salah.");
                isProcessing = false;
            }
        });
    });

    // Jalankan fungsi klik tombol kalau ditekan tombol "Enter" di keyboard
    $('#manual-nis').keypress(function(e) {
        if(e.which == 13) { $('#btn-manual').click(); }
    });
</script>
@endsection
