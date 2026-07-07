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
            background: linear-gradient(-45deg, #0b1320, #174b71, #0a1f35, #001224);
            background-size: 400% 400%;
            animation: gradientBergerak 12s ease infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            margin: 0;
            overflow-x: hidden;
            overflow-y: auto;
        }

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
            background-color: rgba(26, 34, 53, 0.6);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            color: #ffffff;
            margin: auto;
        }

        /* 3. Desain Label & Teks */
        .form-label-custom {
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* 4. Desain Input Form */
        .form-control:focus {
            background-color: #1e293b !important;
            color: white !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }

        .form-control::placeholder {
            color: #64748b !important;
        }

        /* 5. Desain Tombol Login Utama (Lebih Kalem dan Elegan) */
        .btn-login-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }

        .btn-login-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            color: #ffffff;
        }

        /* 6. Desain Tombol Google (Menyatu dengan Tema Gelap) */
        .btn-google-outline {
            background-color: transparent;
            border: 1px solid #334155;
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 12px;
        }

        .btn-google-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border-color: #475569;
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

            .text-center img {
                width: 60px !important;
                margin-bottom: 0.8rem !important;
            }
        }

        /* 8. MEMPERBAIKI WARNA PUTIH AUTOFILL BROWSER (ANTI-BELANG) */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <!-- Bagian Kepala: Menggunakan Logo Asli Sekolah -->
        <div class="text-center mb-4">

            @php
                // Kita cek langsung ke jantung server Vercel
                $pathGambar = public_path('images/logosekolah.jpg');
                $gambarAda = file_exists($pathGambar);
            @endphp

            @if($gambarAda)
                <img src="{{ asset('images/logosekolah.jpg') }}" alt="Logo SMK Pembangunan" class="img-fluid bg-white rounded-circle p-1 shadow-sm" style="width: 75px; height: auto; margin-bottom: 1.2rem;">
            @else
                <div class="alert alert-danger text-start p-2 mx-auto" style="font-size: 0.7rem; max-width: 90%;">
                    <strong>🚨 INFO ERROR VERCEL:</strong><br>
                    File logo tidak ditemukan secara fisik oleh server.<br>
                    <span style="color: #ffcccc;">Path yang dicari Vercel:</span><br>
                    <code>{{ $pathGambar }}</code>
                </div>
            @endif

            <h4 class="fw-bold text-white mb-1">SMK Pembangunan</h4>
            <p class="small mb-0"
                style="color: #cbd5e1; font-size: 0.7rem; letter-spacing: 1px; text-transform: uppercase;">
                Sistem Monitoring Presensi Berbasis QR
            </p>
        </div>

        <!-- Bagian Isi: Form Login -->
        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- KOTAK INPUT EMAIL -->
            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">Email</label>
                <input type="email" name="email" class="form-control shadow-sm" placeholder="Masukkan email"
                    style="background-color: #1e293b; border: 1px solid #334155; color: white; padding: 12px 16px; border-radius: 8px;"
                    required>
            </div>

            <!-- KOTAK INPUT KATA SANDI -->
            <div class="mb-4">
                <label class="form-label text-secondary small fw-bold text-uppercase">Kata Sandi</label>

                <!-- Input Group dengan background gelap agar menyatu sempurna -->
                <div class="input-group shadow-sm"
                    style="border-radius: 8px; overflow: hidden; background-color: #1e293b;">

                    <!-- Kolom Ketik Password -->
                    <input type="password" name="password" id="passwordInput" class="form-control"
                        placeholder="Masukkan kata sandi"
                        style="background-color: transparent; border: 1px solid #334155; border-right: none; color: white; padding: 12px 16px; box-shadow: none;"
                        required>

                    <!-- Tombol Mata -->
                    <button class="btn" type="button" id="togglePassword"
                        style="background-color: transparent; border: 1px solid #334155; border-left: none; color: #94a3b8; padding: 0 16px;">
                        <i class="fas fa-eye-slash" id="eyeIcon"></i>
                    </button>

                </div>
            </div>

            <!-- BARIS INGAT SAYA & LUPA SANDI -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                        style="background-color: #273142; border-color: #334155;">
                    <label class="form-check-label small" for="remember" style="color: #cbd5e1; font-size: 0.8rem;">
                        Ingat Saya
                    </label>
                </div>
                <a href="#" class="text-decoration-none fw-bold" style="color: #3b82f6; font-size: 0.8rem;">
                    Lupa Sandi?
                </a>
            </div>

            <!-- Tombol Login Utama -->
            <button type="submit" class="btn w-100 btn-login-custom mb-4">
                LOGIN
            </button>

            <!-- Garis Pemisah -->
            <div class="d-flex align-items-center mb-4">
                <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
                <span class="mx-3 small" style="color: #64748b; font-size: 0.7rem;">ATAU</span>
                <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.2);">
            </div>

            <!-- Tombol Login Menggunakan Google -->
            <a href="#"
                class="btn btn-google-outline w-100 d-flex justify-content-center align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                    viewBox="0 0 16 16">
                    <path
                        d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                </svg>
                Lanjutkan dengan Google
            </a>
        </form>
    </div> <!-- PENUTUP .login-card -->

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
