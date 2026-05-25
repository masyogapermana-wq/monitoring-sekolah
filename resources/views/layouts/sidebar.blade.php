<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4 fw-bold">Menu Utama</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">

        <!-- MENU ADMIN -->
        @if (Auth::user()->role == 'admin')
            <li class="nav-item">
                <a href="/admin/dashboard"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/siswa" class="nav-link {{ request()->is('admin/siswa') ? 'active' : 'link-dark' }}">
                    Data Siswa
                </a>
            </li>
            <li>
                <a href="{{ route('user.index') }}"
                    class="nav-link {{ request()->is('admin/user') ? 'active' : 'link-dark' }}">
                    Data Guru (User)
                </a>
            </li>
            <li>
                <a href="/admin/pelanggaran"
                    class="nav-link {{ request()->is('admin/pelanggaran') ? 'active' : 'link-dark' }}">
                    Jenis Pelanggaran
                </a>
            </li>
        @endif

        <!-- MENU GURU PIKET -->
        @if (Auth::user()->role == 'piket')
            <li class="nav-item">
                <a href="/piket/dashboard"
                    class="nav-link {{ request()->is('piket/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <!-- Tautan Scan QR yang sudah diperbarui -->
            <li>
                <a href="{{ route('piket.scan') }}"
                    class="nav-link {{ request()->is('piket/scan') ? 'active' : 'link-dark' }}">
                    Scan QR Presensi
                </a>
            </li>
            <!-- Tautan Input Pelanggaran yang sudah diperbarui -->
            <li>
                <a href="{{ route('piket.input') }}"
                    class="nav-link {{ request()->is('piket/input-pelanggaran') ? 'active' : 'link-dark' }}">
                    Input Pelanggaran
                </a>
            </li>
        @endif

        <!-- MENU GURU BK -->
        @if (Auth::user()->role == 'bk')
            <li class="nav-item">
                <a href="/bk/dashboard" class="nav-link {{ request()->is('bk/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('bk.laporan-presensi') }}"
                    class="nav-link {{ request()->is('bk/laporan-presensi') ? 'active' : 'link-dark' }}">
                    Laporan Presensi
                </a>
            </li>
            <a href="{{ route('bk.laporan') }}"
                class="nav-link {{ request()->is('bk/laporan-pelanggaran') ? 'active' : 'link-dark' }}">Laporan
                Pelanggaran</a>
        @endif

    </ul>
</div>
