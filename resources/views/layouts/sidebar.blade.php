<div class="d-flex flex-column bg-light h-100 p-3">

    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4 fw-bold">Menu Utama</span>
    </a>
    <hr>

    <ul class="nav nav-pills flex-column mb-auto flex-grow-1" style="overflow-y: auto;">

        @if (Auth::user()->role == 'admin')
            <li class="nav-item">
                <a href="/admin/dashboard"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/siswa"
                    class="nav-link {{ request()->is('admin/siswa') ? 'active' : 'link-dark' }}">
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
            <li>
                <a href="{{ route('admin.pengaturan') }}"
                    class="nav-link {{ request()->is('admin/pengaturan') ? 'active' : 'link-dark' }}">
                    Pengaturan Jam Masuk
                </a>
            </li>
        @endif

        @if (Auth::user()->role == 'piket')
            <li class="nav-item">
                <a href="/piket/dashboard"
                    class="nav-link {{ request()->is('piket/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('piket.scan') }}"
                    class="nav-link {{ request()->is('piket/scan') ? 'active' : 'link-dark' }}">
                    Scan QR Presensi
                </a>
            </li>
            <li>
                <a href="{{ route('piket.input') }}"
                    class="nav-link {{ request()->is('piket/input-pelanggaran') ? 'active' : 'link-dark' }}">
                    Input Pelanggaran
                </a>
            </li>
        @endif

        @if (Auth::user()->role == 'bk')
            <li class="nav-item">
                <a href="/bk/dashboard"
                    class="nav-link {{ request()->is('bk/dashboard') ? 'active' : 'link-dark' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('bk.laporan-presensi') }}"
                    class="nav-link {{ request()->is('bk/laporan-presensi') ? 'active' : 'link-dark' }}">
                    Laporan Presensi
                </a>
            </li>
            <li>
                <a href="{{ route('bk.laporan') }}"
                    class="nav-link {{ request()->is('bk/laporan-pelanggaran') ? 'active' : 'link-dark' }}">
                    Laporan Pelanggaran
                </a>
            </li>
        @endif

    </ul>

    <hr>
    <div class="mt-auto pt-2">
        <a href="/logout" class="btn btn-danger w-100 fw-bold shadow-sm">
            🚪 Logout
        </a>
    </div>

</div>
