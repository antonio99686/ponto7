<?php
// login/index.php
session_start();
require_once '../admin/function/conexao.php';

// Verificar se já está logado
if (isset($_SESSION['usuario_id']) && isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // Redirecionar baseado no tipo de usuário
    if ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'vendedor') {
        header('Location: ../admin/index.php');
    } else {
        header('Location: ../painel-cliente/index.php');
    }
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id_usuario, nome_completo, email, senha_hash, tipo_usuario, status FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
                if ($usuario['status'] !== 'ativo') {
                    $erro = 'Usuário inativo ou bloqueado. Entre em contato com o suporte.';
                } else {
                    // Salvar dados na sessão
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['usuario_nome'] = $usuario['nome_completo'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
                    $_SESSION['logado'] = true;
                    
                    // Atualizar último login
                    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id_usuario = ?");
                    $stmt->execute([$usuario['id_usuario']]);
                    
                    // Redirecionar baseado no tipo de usuário
                    if ($usuario['tipo_usuario'] === 'admin' || $usuario['tipo_usuario'] === 'vendedor') {
                        header('Location: ../admin/index.php');
                    } else {
                        header('Location: ../painel-cliente/index.php');
                    }
                    exit();
                }
            } else {
                $erro = 'Email ou senha inválidos!';
            }
        } catch(PDOException $e) {
            $erro = 'Erro ao fazer login. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Entrar - Construmix</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0f3b3f;
            --primary-dark: #0a2a2d;
            --accent: #e8850c;
            --accent-hover: #d0750a;
            --gray-50: #f7f8fa;
            --gray-100: #edf0f3;
            --gray-200: #dce1e8;
            --gray-500: #7a8599;
            --white: #ffffff;
            --danger: #d94a5a;
            --success: #2d9b7a;
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            --radius: 16px;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f3b3f 0%, #1a5a5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--white);
            border-radius: var(--radius);
            padding: 48px 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: var(--shadow);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .logo {
            font-size: 3rem;
            margin-bottom: 8px;
        }

        .login-header h1 {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 800;
        }

        .login-header h1 span {
            color: var(--accent);
        }

        .login-header p {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 59, 63, 0.1);
        }

        .form-group input::placeholder {
            color: var(--gray-500);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 59, 63, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fee2e2;
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-left: 4px solid var(--danger);
            display: <?php echo $erro ? 'block' : 'none'; ?>;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.9rem;
            color: var(--gray-500);
        }

        .login-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .btn-cadastro {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-cadastro:hover {
            background: var(--primary);
            color: var(--white);
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle input {
            padding-right: 48px;
        }

        .password-toggle .toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--gray-500);
            padding: 4px;
        }

        .password-toggle .toggle-btn:hover {
            color: var(--primary);
        }

        .voltar-loja {
            display: inline-block;
            margin-top: 16px;
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .voltar-loja:hover {
            color: var(--primary);
        }

        /* Badge de tipo de conta */
        .tipo-conta {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-top: 4px;
        }

        .tipo-conta.admin {
            background: #fef3c7;
            color: #92400e;
        }

        .tipo-conta.cliente {
            background: #dbeafe;
            color: #1e40af;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🛠️</div>
            <h1>Construmix</h1>
            <p>Entre na sua conta para acompanhar seus pedidos</p>
        </div>

        <?php if ($erro): ?>
        <div class="error-message"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">📧 Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="seu@email.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="senha">🔒 Senha</label>
                <div class="password-toggle">
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        placeholder="Digite sua senha"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword()">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">🔑 Entrar</button>
        </form>

        <div class="divider">ou</div>

        <a href="cadastro.php" class="btn-cadastro">📝 Criar uma conta</a>

        <div class="login-footer">
            <a href="../index.php" class="voltar-loja">← Voltar para a loja</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('senha');
            const btn = document.querySelector('.toggle-btn');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>