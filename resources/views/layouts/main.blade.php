<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SMK Pembangunan</title>

    <!-- Memanggil CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memanggil FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-light: #0b1320;
            --sidebar-bg: #151e2d;
            --topbar-bg: #1a2235;
            --card-bg: #1e293b;
            --text-muted: #94a3b8;
        }

        body {
            background-color: var(--bg-light);
            color: #e2e8f0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            color: #ffffff;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            width: 260px;
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 4px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 8px;
        }

        .main-content {
            width: 100%;
            transition: all 0.3s ease;
        }

        .topbar {
            background-color: var(--topbar-bg);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            color: #ffffff;
        }

        .btn-logout {
            background-color: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border: 1px solid rgba(255, 71, 87, 0.2);
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background-color: #ff4757;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                z-index: 1040;
            }

            .sidebar.show-mobile {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.6);
                z-index: 1030;
                backdrop-filter: blur(2px);
            }

            .sidebar-overlay.show-mobile {
                display: block;
            }

            .topbar {
                padding: 15px;
            }
        }
    </style>
</head>

<body class="d-flex">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- BAGIAN KIRI: SIDEBAR -->
    <div class="sidebar d-flex flex-column py-4" id="sidebarMenu">
        <div class="text-center mb-4 px-3">
            <!-- INI BAGIAN YANG DIPERBAIKI: Tag HTML disatukan agar width 65px berfungsi -->
            <img src="{{ asset('images/logosekolah.jpg') }}" alt="Logo SMK Pembangunan" class="bg-white rounded-circle p-1 mb-2 shadow-sm" style="width: 65px;">
            <h5 class="fw-bold mb-0 text-white" style="letter-spacing: 0.5px;">SMK Pembangunan</h5>

            <!-- Label Panel dipisah menggunakan if mandiri -->
            @if (auth()->user()->role == 'admin')
                <small class="text-uppercase" style="color: #00d2ff; font-size: 0.7rem; font-weight: 600;">Panel Administrator</small>
            @endif

            @if (auth()->user()->role == 'piket')
                <small class="text-uppercase" style="color: #ffab00; font-size: 0.7rem; font-weight: 600;">Panel Guru Piket</small>
            @endif

            @if (auth()->user()->role == 'bk')
                <small class="text-uppercase" style="color: #2ed573; font-size: 0.7rem; font-weight: 600;">Panel Guru BK</small>
            @endif
        </div>

        <ul class="nav flex-column mb-auto mt-2">

            <!-- MENU KHUSUS ADMIN -->
            @if (auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/siswa') }}" class="nav-link {{ request()->is('admin/siswa') || request()->is('admin/siswa/*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i> Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/user') }}" class="nav-link {{ request()->is('admin/user') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher"></i> Data Guru
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/pelanggaran" class="nav-link {{ request()->is('admin/pelanggaran') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i> <span>Jenis Pelanggaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sanksi.index') }}" class="nav-link {{ request()->routeIs('sanksi.*') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i> <span>Sanksi Edukatif</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/admin/pengaturan') }}" class="nav-link {{ request()->is('admin/pengaturan') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
            @endif

            <!-- ========================================== -->
            <!-- 1. MENU KHUSUS GURU PIKET -->
            <!-- ========================================== -->
            @if (auth()->user()->role == 'piket')
                <li class="nav-item">
                    <a href="{{ url('/piket/dashboard') }}" class="nav-link {{ request()->is('piket/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                <!-- Menu Scan QR -->
                <li class="nav-item">
                    <a href="{{ route('piket.scan') }}" class="nav-link {{ request()->is('piket/scan') ? 'active' : '' }}">
                        <i class="fas fa-qrcode"></i> Scan QR Presensi
                    </a>
                </li>

                <!-- Menu Presensi Manual -->
                <li class="nav-item">
                    <a href="{{ route('piket.manual') }}" class="nav-link {{ request()->is('piket/manual') ? 'active' : '' }}">
                        <i class="fas fa-keyboard"></i> Presensi Manual
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('piket.input') }}" class="nav-link {{ request()->is('piket/input-pelanggaran') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-circle"></i> Input Pelanggaran
                    </a>
                </li>
            @endif


            <!-- ========================================== -->
            <!-- 2. MENU KHUSUS GURU BK -->
            <!-- ========================================== -->
            @if (auth()->user()->role == 'bk')
                <li class="nav-item">
                    <a href="{{ route('bk.dashboard') }}" class="nav-link {{ request()->is('bk/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bk.laporan-presensi') }}" class="nav-link {{ request()->is('bk/laporan-presensi') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Laporan Presensi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bk.laporan') }}" class="nav-link {{ request()->is('bk/laporan-pelanggaran') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle"></i> Laporan Pelanggaran
                    </a>
                </li>
            @endif

        </ul>
    </div>

    <!-- BAGIAN KANAN: KONTEN UTAMA -->
    <div class="main-content d-flex flex-column">

        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">

            <!-- Grup Kiri: Tombol Menu HP & Widget Tanggal (Baru) -->
            <div class="d-flex align-items-center">
                <!-- Tombol Menu HP -->
                <button class="btn text-white d-md-none p-1 me-3" id="btnToggleMenu" style="border: none; background: transparent; font-size: 1.2rem;">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Widget Tanggal & Info Sekolah (Hanya muncul di PC/Tablet) -->
                <div class="d-none d-md-flex align-items-center">
                    <div class="rounded p-2 me-2 d-flex justify-content-center align-items-center shadow-sm" style="background-color: rgba(0, 210, 255, 0.1); width: 38px; height: 38px;">
                        <i class="fas fa-calendar-alt text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-bold" style="font-size: 0.85rem;">
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                        </h6>
                        <small style="color: #94a3b8; font-size: 0.65rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Tahun Ajaran Aktif
                        </small>
                    </div>
                </div>
            </div>

            <!-- Grup Kanan: Profil dan Logout -->
            <div class="d-flex align-items-center">
                <span class="me-3 fw-semibold text-light small d-none d-sm-flex align-items-center">
                    <i class="fas fa-user-circle fs-5 me-2 text-info"></i>
                    {{ auth()->user()->name }}

                    @if (auth()->user()->role == 'admin')
                        <span class="badge bg-primary ms-2" style="font-size: 0.65rem;">Admin</span>
                    @endif
                    @if (auth()->user()->role == 'piket')
                        <span class="badge ms-2" style="background-color: #ffab00; color: #000; font-size: 0.65rem;">Piket</span>
                    @endif
                    @if (auth()->user()->role == 'bk')
                        <span class="badge bg-success ms-2" style="font-size: 0.65rem;">BK</span>
                    @endif
                </span>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-logout btn-sm rounded-3 px-2 px-sm-3 fw-bold" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i> <span class="d-none d-sm-inline ms-1">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Ruang Konten -->
        <div class="p-3 p-md-4 flex-grow-1" style="overflow-x: hidden;">
            @yield('content')
        </div>

    </div>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SCRIPT TAMBAHAN UNTUK INTERAKSI MENU DI HP -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnToggle = document.getElementById('btnToggleMenu');
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            if (btnToggle) {
                btnToggle.addEventListener('click', function() {
                    sidebar.classList.add('show-mobile');
                    overlay.classList.add('show-mobile');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show-mobile');
                    overlay.classList.remove('show-mobile');
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
