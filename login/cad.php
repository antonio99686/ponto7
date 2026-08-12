<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto 7 · Ferragens</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ============================================
           CSS PARA LOGIN - ESTILO PONTO 7
           Loja de Ferragens e Materiais de Construção
           ============================================ */

        /* ===== RESET E BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        body {
            background: #edebe7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(190, 130, 50, 0.04) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(190, 130, 50, 0.04) 0%, transparent 50%);
        }

        /* ===== CONTAINER PRINCIPAL ===== */
        .login-container {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 32px;
            padding: 44px 38px 48px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.06),
                0 8px 24px rgba(0, 0, 0, 0.03),
                0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid #e0dad2;
            position: relative;
            transition: transform 0.2s ease;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        /* ===== ELEMENTOS DECORATIVOS ===== */
        .login-container::before {
            content: "⚙";
            position: absolute;
            top: 18px;
            right: 28px;
            font-size: 1.8rem;
            color: #c9b8a4;
            opacity: 0.25;
            transform: rotate(25deg);
        }

        .login-container::after {
            content: "🔩";
            position: absolute;
            bottom: 16px;
            left: 24px;
            font-size: 1.8rem;
            color: #c9b8a4;
            opacity: 0.2;
            transform: rotate(-10deg);
        }

        /* ===== CABEÇALHO ===== */
        .login-header {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .login-header .logo {
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #1a1a1a;
        }

        .login-header .logo span {
            font-weight: 300;
            color: #8a7a6a;
        }

        .login-header .badge {
            font-size: 0.6rem;
            background: #d4c9bc;
            color: #2d2d2d;
            padding: 2px 14px;
            border-radius: 30px;
            letter-spacing: 0.8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* ===== SUBTÍTULO ===== */
        .login-subtitle {
            text-align: center;
            color: #6b6256;
            font-size: 0.9rem;
            font-weight: 400;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0ebe3;
        }

        .login-subtitle i {
            color: #b8860b;
            margin: 0 4px;
        }

        /* ===== FORMULÁRIO ===== */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ===== GRUPO DE CAMPO ===== */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: 500;
            font-size: 0.82rem;
            color: #2d2d2d;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #b8860b;
            font-size: 0.8rem;
            width: 18px;
        }

        /* ===== CAMPO DE INPUT ===== */
        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f5f2ec;
            border-radius: 60px;
            border: 1.5px solid #e0dad2;
            padding: 0 20px;
            transition: all 0.25s ease;
        }

        .input-wrapper:focus-within {
            border-color: #b8860b;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(184, 134, 11, 0.08);
        }

        .input-wrapper .input-icon {
            color: #a89b88;
            font-size: 0.9rem;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .input-wrapper input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 15px 0;
            font-size: 0.95rem;
            outline: none;
            color: #1e1e1e;
            transition: 0.2s;
        }

        .input-wrapper input::placeholder {
            color: #b3a79a;
            font-weight: 400;
        }

        .input-wrapper input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #f5f2ec inset !important;
            -webkit-text-fill-color: #1e1e1e !important;
        }

        .input-wrapper:focus-within input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
        }

        /* ===== BOTÃO MOSTRAR SENHA ===== */
        .toggle-password {
            background: none;
            border: none;
            color: #b3a79a;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 8px 0 8px 8px;
            transition: 0.2s;
            flex-shrink: 0;
        }

        .toggle-password:hover {
            color: #3d352b;
        }

        /* ===== OPÇÕES EXTRAS ===== */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0 10px 0;
            font-size: 0.85rem;
        }

        .form-options .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #3d352b;
            cursor: pointer;
        }

        .form-options .remember-me input[type="checkbox"] {
            accent-color: #b8860b;
            width: 16px;
            height: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-options .forgot-link {
            color: #b8860b;
            text-decoration: none;
            font-weight: 500;
            transition: 0.15s;
            border-bottom: 1.5px solid transparent;
            font-size: 0.82rem;
        }

        .form-options .forgot-link:hover {
            border-bottom-color: #b8860b;
        }

        .form-options .forgot-link i {
            margin-right: 4px;
            font-size: 0.7rem;
        }

        /* ===== BOTÃO DE LOGIN ===== */
        .btn-login {
            width: 100%;
            background: #b8860b;
            border: none;
            border-radius: 60px;
            padding: 16px 24px;
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 4px;
            box-shadow: 0 4px 16px rgba(184, 134, 11, 0.15);
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: 0.5s;
        }

        .btn-login:hover::after {
            left: 100%;
        }

        .btn-login:hover {
            background: #a0750a;
            transform: scale(1.01);
            box-shadow: 0 8px 24px rgba(184, 134, 11, 0.2);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login i {
            font-size: 0.95rem;
        }

        /* ===== LINK CADASTRO ===== */
        .signup-section {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f0ebe3;
            font-size: 0.9rem;
            color: #5a5146;
        }

        .signup-section a {
            color: #1a1a1a;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 2px solid #d4c9bc;
            padding-bottom: 2px;
            transition: 0.2s;
        }

        .signup-section a:hover {
            border-bottom-color: #b8860b;
            color: #b8860b;
        }

        .signup-section a i {
            margin-left: 4px;
            font-size: 0.7rem;
        }

        /* ===== SELO DECORATIVO ===== */
        .selo-footer {
            text-align: center;
            margin-top: 16px;
        }

        .selo-footer .selo {
            display: inline-block;
            background: #ede9e2;
            border-radius: 60px;
            padding: 4px 20px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #4a4036;
        }

        .selo-footer .selo i {
            margin-right: 6px;
            color: #b8860b;
        }

        /* ===== ANIMAÇÃO ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px 36px;
                border-radius: 24px;
                max-width: 100%;
            }

            .login-container::before,
            .login-container::after {
                display: none;
            }

            .login-header .logo {
                font-size: 2rem;
            }

            .login-header .badge {
                font-size: 0.5rem;
                padding: 1px 10px;
            }

            .login-subtitle {
                font-size: 0.8rem;
                margin-bottom: 24px;
                padding-bottom: 16px;
            }

            .input-wrapper {
                padding: 0 14px;
            }

            .input-wrapper input {
                padding: 13px 0;
                font-size: 0.9rem;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .btn-login {
                padding: 14px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 380px) {
            .login-container {
                padding: 24px 16px 28px;
            }

            .login-header .logo {
                font-size: 1.6rem;
            }
        }

        /* ===== SCROLLBAR ESTILIZADA ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #edebe7;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4c9bc;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b8860b;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- CABEÇALHO -->
        <div class="login-header">
            <span class="badge">FERRAGEM</span>
            <span class="logo">PONTO <span>7</span></span>
        </div>

        <div class="login-subtitle">
            <i class="fas fa-hard-hat"></i> Acesse sua conta <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
        </div>

        <!-- FORMULÁRIO DE LOGIN -->
        <form action="index.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="cpf"><i class="fas fa-id-card"></i> CPF</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" required>
                </div>
            </div>

            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <div class="input-wrapper">
                    <i class="fas fa-key input-icon"></i>
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" id="toggleSenha" aria-label="mostrar senha">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" checked> Lembrar-me
                </label>
                <a href="#" class="forgot-link"><i class="fas fa-question-circle"></i> Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>

        <div class="signup-section">
            Não tem conta? <a href="#">Cadastre-se <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="selo-footer">
            <span class="selo"><i class="fas fa-tools"></i> construção · confiança</span>
        </div>
    </div>

    <!-- SCRIPT PARA MOSTRAR/OCULTAR SENHA -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSenha');
            const senhaInput = document.getElementById('senha');

            if (toggleBtn && senhaInput) {
                toggleBtn.addEventListener('click', function() {
                    const type = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    senhaInput.setAttribute('type', type);
                    
                    // Troca o ícone
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        });
    </script>

</body>
</html>