<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Automatic Attendance System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

    <main class="login-wrapper">

        <section class="login-brand">
            <div class="login-brand-content">
                <div class="brand-logo">
                    <i class="bi bi-fingerprint"></i>
                </div>

                <h1>Automatic Attendance System</h1>

                <p>
                    Secure student attendance monitoring using
                    RFID identification and fingerprint verification.
                </p>

                <div class="login-feature">
                    <i class="bi bi-shield-check"></i>
                    <span>Secure identification</span>
                </div>

                <div class="login-feature">
                    <i class="bi bi-wifi"></i>
                    <span>ESP32 Wi-Fi integration</span>
                </div>

                <div class="login-feature">
                    <i class="bi bi-graph-up"></i>
                    <span>Real-time attendance monitoring</span>
                </div>
            </div>
        </section>

        <section class="login-form-section">

            <div class="login-form-container">

                <div class="login-mobile-logo">
                    <i class="bi bi-fingerprint"></i>
                </div>

                <span class="text-muted small fw-semibold">
                    SCHOOL ADMINISTRATION
                </span>

                <h2 class="login-title">Welcome Back!</h2>

                <p class="text-muted mb-4">
                    Sign in to access your attendance dashboard.
                </p>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address
                        </label>

                        <div class="input-group login-input">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                autocomplete="username"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-group login-input">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="input-group-text password-toggle"
                                id="togglePassword"
                                aria-label="Show or hide password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="remember"
                            id="remember"
                        >

                        <label
                            class="form-check-label text-muted"
                            for="remember"
                        >
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-orange w-100">
                        Sign In
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                </form>

                <div class="login-footer">
                    Automatic Student Attendance Monitoring System
                </div>

            </div>

        </section>

    </main>

</body>
</html>