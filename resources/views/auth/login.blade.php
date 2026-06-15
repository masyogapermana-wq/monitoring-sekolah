<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Pembangunan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .icon-logo {
            font-size: 4rem; /* Ukuran icon besar */
            color: #0d6efd; /* Warna biru primary */
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="card card-login bg-white">
        <div class="text-center">
            <div class="icon-logo">
    <img src="{{ secure_asset('img/kikil.jpg') }}" alt="Logo SMK" style="width: 80px; height: auto;">
</div>

            <h5 class="fw-bold mb-1">SMK PEMBANGUNAN</h5>
            <p class="text-muted small mb-4">Sistem Monitoring Presensi & Pelanggaran</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@smk.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukan password..." required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">LOGIN</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Copyright 2026 - Yoga Permana</small>
        </div>
    </div>

</body>
</html>
