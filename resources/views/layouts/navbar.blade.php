<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            SMK PEMBANGUNAN
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-white">
                        Halo, <strong>{{ Auth::user()->name }}</strong> ({{ ucfirst(Auth::user()->role) }})
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white btn-sm ms-2 px-3" href="/logout">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
