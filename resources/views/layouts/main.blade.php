<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Siswa - SMK Pembangunan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Biar Sidebar nempel kiri dan konten di kanan */
        .wrapper {
            display: flex;
            width: 100%;
        }
        .content {
            width: 100%;
            padding: 20px;
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
