<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title>Monitoring Siswa - SMK Pembangunan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Biar konten gak ketutup navbar yang fixed-top */
       /* Biar konten gak ketutup navbar yang fixed-top */
        body {
            padding-top: 56px;
            overflow-x: hidden;
            background-color: #f4f6f9; /* Opsional: Biar background agak abu-abu manis */
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* 🔥 ATURAN SIDEBAR DESKTOP (LAPTOP) */
        /* 🔥 ATURAN SIDEBAR DESKTOP (LAPTOP) */
        #sidebar-container {
            width: 250px;
            flex-shrink: 0;
            height: calc(100vh - 56px); /* Tinggi pas dari bawah navbar sampai ujung layar */
            position: sticky;
            top: 56px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            /* overflow-y: auto; <-- INI DIHAPUS, pindah ke tag <ul> di sidebar.blade.php */
        }

        .content {
            width: calc(100% - 250px);
            padding: 20px;
            min-height: calc(100vh - 56px);
        }

        /* 🔥 ATURAN SIDEBAR KHUSUS HP (Di bawah 768px) */
        @media (max-width: 768px) {
            #sidebar-container {
                position: fixed;
                left: -250px; /* Ngumpet */
                z-index: 999;
                transition: 0.3s ease-in-out;
                box-shadow: 2px 0 5px rgba(0,0,0,0.2);
            }

            #sidebar-container.muncul {
                left: 0;
            }

            .content {
                width: 100%;
                padding: 15px;
            }

            #sidebar-overlay {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                width: 100%;
                height: calc(100vh - 56px);
                background: rgba(0,0,0,0.5);
                z-index: 998;
            }
            #sidebar-overlay.muncul {
                display: block;
            }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <div class="wrapper">
        <div id="sidebar-overlay"></div>

        <div id="sidebar-container">
            @include('layouts.sidebar')
        </div>

        <div class="content">
            @if(Auth::check())
            <div class="alert alert-info py-2 px-3 mb-4 shadow-sm" style="border-left: 5px solid #0d6efd;">
                👋 Halo, <strong>{{ Auth::user()->name }}</strong> ({{ ucfirst(Auth::user()->role) }})
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const btnToggle = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar-container');
        const overlay = document.getElementById('sidebar-overlay');

        // Kalau tombol ☰ ditekan
        if(btnToggle) {
            btnToggle.addEventListener('click', function() {
                sidebar.classList.toggle('muncul');
                overlay.classList.toggle('muncul');
            });
        }

        // Kalau layar gelap / luar sidebar disentuh, sidebar nutup lagi
        if(overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('muncul');
                overlay.classList.remove('muncul');
            });
        }
    </script>
</body>
</html>
