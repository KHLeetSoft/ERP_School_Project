
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accountant Login - {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary-color: #374151;
            --success-color: #2ecc71;
            --info-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(30, 64, 175, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: containerSlideIn 0.8s ease-out;
        }

        @keyframes containerSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2.25rem 2rem;
            text-align: center;
            position: relative;
        }

        .school-logo {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(15px);
            border: 3px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .school-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .school-logo::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
            border-radius: 50%;
            z-index: -1;
        }

        .school-name {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            opacity: 0.95;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 1rem;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
            background: #fff;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .input-group { position: relative; }
        .input-group-text {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #6c757d; z-index: 3;
        }
        .input-group .form-control { padding-left: 3rem; padding-right: 3rem; }
        .password-toggle {
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #6c757d; cursor: pointer; z-index: 3; font-size: 1.1rem; transition: all 0.3s ease;
        }
        .password-toggle:hover { color: var(--primary-color); transform: translateY(-50%) scale(1.1); }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 1rem;
            padding: 0.9rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login::before {
            content: '';
            position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-login:hover::before { left: 100%; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(30,64,175,0.35); }
        .btn-login:active { transform: translateY(0); }

        .form-check { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
        .form-check-input { width: 1.25rem; height: 1.25rem; border-radius: 0.5rem; border: 2px solid #e9ecef; }
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        .form-check-label { color: #6c757d; font-weight: 500; }

        .login-footer { text-align: center; padding: 1rem 2rem 2rem; border-top: 1px solid #e9ecef; }
        .login-footer a { color: var(--primary-color); text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
        .login-footer a:hover { color: var(--primary-dark); text-decoration: underline; }

        .alert { border: none; border-radius: 1rem; padding: 1rem 1.5rem; margin-bottom: 1.5rem; font-weight: 500; }
        .alert-danger { background: linear-gradient(135deg, #f8d7da, #f5c6cb); color: #721c24; }
        .alert-success { background: linear-gradient(135deg, #d4edda, #c3e6cb); color: #155724; }

        .invalid-feedback { display: block; color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem; font-weight: 500; }

        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .back-to-home { position: absolute; top: 1rem; left: 1rem; color: white; text-decoration: none; font-size: 1.25rem; transition: all 0.3s ease; z-index: 2; }
        .back-to-home:hover { color: rgba(255,255,255,0.9); transform: translateX(-5px); }

        @media (max-width: 480px) {
            .login-container { margin: 0.5rem; border-radius: 1.5rem; }
            .login-header { padding: 1.75rem; }
            .login-body { padding: 1.5rem; }
            .login-footer { padding: 1rem 1.5rem 1.5rem; }
        }
    </style>
</head>
<body>
    @php
        $schoolName = config('app.school_name', config('app.name', 'School Portal'));
        $schoolLogoPath = config('app.school_logo', 'images/school-logo.png');
        $schoolLogo = asset($schoolLogoPath);
    @endphp
    <div class="login-container" data-aos="zoom-in" data-aos-duration="800">
        <a href="{{ url('/') }}" class="back-to-home" title="Back to Home">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <div class="login-header">
            <div class="school-logo">
                @if(file_exists(public_path($schoolLogoPath)))
                    <img src="{{ $schoolLogo }}" alt="{{ $schoolName }} Logo">
                @else
                    <i class="fas fa-school"></i>
                @endif
            </div>
            <div class="school-name">{{ $schoolName }}</div>
            <div class="login-subtitle">Accountant Portal — Secure Access</div>
        </div>
        
        <div class="login-body">
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('accountant.login') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email address"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-key"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                </button>
            </form>
        </div>
        
        <div class="login-footer">
            <p>Don't have an account? <a href="{{ route('accountant.register') }}">Register here</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Form submission with loading state
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');

            loginForm.addEventListener('submit', function() {
                btnText.innerHTML = '<span class="loading"></span> Signing In...';
                loginBtn.disabled = true;
            });

            // Auto-hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                    this.parentElement.style.transition = 'transform 0.3s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Add typing animation to school name
            const schoolName = document.querySelector('.school-name');
            if (schoolName) {
                const text = schoolName.textContent;
                schoolName.textContent = '';
                let i = 0;
                const typeWriter = () => {
                    if (i < text.length) {
                        schoolName.textContent += text.charAt(i);
                        i++;
                        setTimeout(typeWriter, 100);
                    }
                };
                setTimeout(typeWriter, 1000);
            }
        });

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
