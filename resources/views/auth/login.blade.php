<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMK Pembangunan</title>
    <!-- Memanggil CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link untuk memanggil ikon Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* ==========================================================
           1. ANIMASI LATAR BELAKANG MENGALIR (FLUID GRADIENT)
           ========================================================== */
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;

            /* Perpaduan warna gelap yang mewah */
            background: linear-gradient(-45deg, #0b1320, #174b71, #0a1f35, #001224);
            background-size: 400% 400%;

            /* Memanggil animasi agar terus berjalan */
            animation: gradientBergerak 12s ease infinite;

            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            margin: 0;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Mesin Penggerak Animasi Latar Belakang */
        @keyframes gradientBergerak {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* ==========================================================
           2. DESAIN KOTAK LOGIN (EFEK KACA / GLASSMORPHISM)
           ========================================================== */
        .login-card {
            /* Membuat kotak agak transparan */
            background-color: rgba(26, 34, 53, 0.6);

            /* Efek kaca buram (membias warna dari latar belakang yang bergerak) */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);

            /* Garis pinggir tipis agar lebih bertekstur */
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);

            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            color: #ffffff;
            margin: auto;
        }

        /* 3. Desain Label Kecil & Elegan */
        .form-label-custom {
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* 4. Desain Input Form Mode Gelap (Transparan) */
        .form-control-dark {
            background-color: rgba(39, 49, 66, 0.7);
            /* Ikut dibuat sedikit tembus pandang */
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            padding: 1rem 1.2rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-control-dark::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            background-color: #1e293b !important;
            color: white !important;
            border-color: #3b82f6 !important;
            /* Warna biru saat diklik */
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }

        /* 5. Efek Tombol Neon Bersinar */
        .btn-glow {
            background: linear-gradient(135deg, #00d2ff 0%, #00a1ff 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 161, 255, 0.4);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 161, 255, 0.6);
            color: #ffffff;
        }

        /* 6. Desain Tombol Google Putih Bersih */
        .btn-google-dark {
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
            color: #0f172a;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: all 0.2s;
            padding: 0.8rem;
        }

        .btn-google-dark:hover {
            background-color: #ffffff;
            transform: translateY(-1px);
        }

        /* 7. Pengaturan Layar HP */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .login-card {
                padding: 2rem 1.5rem;
                margin: auto 10px;
                width: auto;
            }

            .form-control-dark,
            .btn-glow,
            .btn-google-dark {
                padding: 0.8rem 1rem;
            }

            .text-center img {
                width: 60px !important;
                margin-bottom: 0.8rem !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- Bagian Kepala: Menggunakan Logo Asli Sekolah -->
        <div class="text-center mb-4">
            <img src="/images/logo-smk.png.jpg" alt="Logo SMK Pembangunan"
                class="img-fluid bg-white rounded-circle p-1 shadow-sm"
                style="width: 75px; height: auto; margin-bottom: 1.2rem;">

            <h4 class="fw-bold text-white mb-1">SMK Pembangunan</h4>
            <p class="small mb-0"
                style="color: #cbd5e1; font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">
                Sistem Monitoring Presensi Berbasis QR
            </p>
        </div>

        <!-- Bagian Isi: Form Login -->
        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label-custom text-uppercase">Email</label>
                <input type="email" name="email" class="form-control form-control-dark" placeholder="admin@smk.com"
                    required>
            </div>

            <!-- KATA SANDI -->
            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">Kata Sandi</label>

                <!-- Input Group dengan shadow dan rounded corners -->
                <div class="input-group shadow-sm" style="border-radius: 8px; overflow: hidden;">

                    <!-- Input Password (Background disamakan dengan Email, border kanan dihilangkan) -->
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Masukkan kata sandi"
                        style="background-color: #1e293b; border: 1px solid #334155; border-right: none; color: white; padding: 12px 16px; box-shadow: none;"
                        required>

                    <!-- Tombol Mata (Background disamakan, border kiri dihilangkan agar menyatu) -->
                    <button class="btn" type="button" id="togglePassword"
                        style="background-color: #1e293b; border: 1px solid #334155; border-left: none; color: #94a3b8; padding: 0 16px;">
                        <i class="fas fa-eye-slash" id="eyeIcon"></i>
                    </button>

                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                        style="background-color: #273142; border-color: #334155;">
                    <label class="form-check-label small" for="remember"
                        style="color: #cbd5e1; font-size: 0.8rem;">Ingat Saya</label>
                </div>
                <a href="#" class="text-decoration-none fw-bold" style="color: #00d2ff; font-size: 0.8rem;">Lupa
                    Sandi?</a>
            </div>

            <!-- Tombol Login Bercahaya -->
            <button type="submit" class="btn w-100 btn-glow mb-4">
                LOGIN
            </button>

            <!-- Garis Pemisah -->
            <div class="d-flex align-items-center mb-4">
                <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
                <span class="mx-3 small" style="color: #94a3b8; font-size: 0.7rem;">ATAU</span>
                <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
            </div>

            <!-- Tombol Login Menggunakan Google -->
            <a href="#"
                class="btn btn-google-dark w-100 d-flex justify-content-center align-items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    class="text-dark" viewBox="0 0 16 16">
                    <path
                        d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                </svg>
                Lanjutkan dengan Google
            </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
