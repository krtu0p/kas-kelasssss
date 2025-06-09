<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(to right, #4D194D, #006466);
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
        }

        .login-icon img {
            width: 70px;
            height: auto;
            margin-bottom: 12px;
            transition: width 0.3s ease;
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 32px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            position: relative;
            margin-bottom: 5px;
            padding-bottom: 22px;
            width: 100%;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            text-align: left;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            width: 100%;
            padding: 14px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif;
            transition: padding 0.3s ease, font-size 0.3s ease, border-color 0.2s ease, background-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #006466;
            box-shadow: 0 0 0 2px rgba(0, 100, 102, 0.2);
        }

        #togglePassword {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            margin-top: 16px;
            border: none;
            border-radius: 99px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login {
            background: linear-gradient(to right, #006466, #4D194D);
        }

        .btn-student {
            background: linear-gradient(to right, #065A60, #1B3A4B);
        }

        .student-link {
            display: block;
            width: 100%;
            text-decoration: none;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            bottom: 2px;
            left: 0;
            display: none;
        }

        .form-control.error {
            border-color: #e74c3c;
            background-color: #fdd;
        }

        .error-message.show {
            display: block;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px;
            }

            .login-icon img {
                width: 60px;
            }

            .login-title {
                font-size: 28px;
                margin-bottom: 24px;
            }

            .form-control {
                padding: 12px 40px;
                font-size: 15px;
            }

            .btn {
                padding: 12px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-icon">
            <img src="//lms.skensa.id/pluginfile.php/1/theme_moove/logo/1749082852/logo-lms-2.png" class="logo" alt="LMS-SKENSA">
        </div>
        <h1 class="login-title">Log In</h1>

        <form method="POST" action="{{ route('login.process') }}" class="login-form" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
                <span class="error-message" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="masukkan password" required>
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>
                <span class="error-message" id="passwordError"></span>
            </div>

            <button type="submit" class="btn btn-login" id="loginButton">Log in as Bendahara</button>
        </form>

        <a href="{{ route('siswa.index') }}" class="student-link">
            <button type="button" class="btn btn-student" id="loginSiswaButton">Log in as Siswa</button>
        </a>
    </div>

    <script>
        // --- Elemen DOM ---
        const form = document.getElementById('loginForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const loginButton = document.getElementById('loginButton');
        const loginSiswaButton = document.getElementById('loginSiswaButton');
        const togglePassword = document.getElementById('togglePassword');

        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        // --- Fungsi untuk Tampilkan/Sembunyikan Password ---
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // --- Event listener untuk validasi real-time ---
        email.addEventListener('input', validateEmail);
        password.addEventListener('input', validatePassword);

        // Buat nampilin spinner biar makin keren
        function showSpinner(button, loadingText = "Memuat...") {
            button.disabled = true;
            button.innerHTML = `${loadingText} <i class="fas fa-spinner fa-spin"></i>`;
        }

        // --- Logika Pengiriman Form ---
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const isFormValid = validateForm();

            if (isFormValid) {
                // Panggil fungsi spinner untuk tombol login bendahara
                showSpinner(loginButton, "Log In as Bendahara")

                // Setelah jeda, kirim form. Tombol akan tetap disabled karena halaman akan berpindah.
                setTimeout(() => {
                    form.submit();
                }, 500);
            }
        });

        // --- Event listener untuk Tombol Siswa ---
        loginSiswaButton.addEventListener('click', function(event) {
            // Panggil fungsi spinner untuk tombol login siswa
            showSpinner(loginSiswaButton, "Log in as Siswa");
        });

        // --- Fungsi Validasi Modular ---
        function validateForm() {
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            return isEmailValid && isPasswordValid;
        }

        function validateEmail() {
            if (email.value.trim() === '') {
                showError(email, emailError, 'Email tidak boleh kosong!');
                return false;
            } else if (!isValidEmail(email.value)) {
                showError(email, emailError, 'Format email tidak valid!');
                return false;
            } else {
                hideError(email, emailError);
                return true;
            }
        }

        function validatePassword() {
            if (password.value.trim() === '') {
                showError(password, passwordError, 'Password tidak boleh kosong!');
                return false;
            } else if (password.value.length < 5) {
                showError(password, passwordError, 'Password minimal 5 karakter.');
                return false;
            } else {
                hideError(password, passwordError);
                return true;
            }
        }

        // --- Fungsi Bantuan (Helper Functions) ---
        function showError(inputElement, errorElement, message) {
            inputElement.classList.add('error');
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }

        function hideError(inputElement, errorElement) {
            inputElement.classList.remove('error');
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }

        function isValidEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>

</body>

</html>