<?php
// login/cadastro.php
session_start();
require_once '../admin/function/conexao.php';

// Verificar se já está logado
if (isset($_SESSION['cliente_id']) && isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: ../painel-cliente/index.php');
    exit();
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
    $telefone = $_POST['telefone'] ?? '';
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Preencha todos os campos obrigatórios!';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido!';
    } else {
        try {
            // Verificar se o email já existe
            $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $erro = 'Este email já está cadastrado!';
            } else {
                // Inserir novo usuário
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome_completo, email, senha_hash, cpf, telefone, tipo_usuario, status) 
                                       VALUES (?, ?, ?, ?, ?, 'cliente', 'ativo')");
                $stmt->execute([$nome, $email, $senha_hash, $cpf, $telefone]);
                
                $sucesso = '✅ Conta criada com sucesso! Faça login para continuar.';
                
                // Limpar campos
                $_POST = [];
            }
        } catch(PDOException $e) {
            $erro = 'Erro ao criar conta. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📝 Criar Conta - Construmix</title>
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

        .cadastro-container {
            background: var(--white);
            border-radius: var(--radius);
            padding: 48px 40px;
            max-width: 480px;
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

        .cadastro-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .cadastro-header .logo {
            font-size: 3rem;
            margin-bottom: 8px;
        }

        .cadastro-header h1 {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 800;
        }

        .cadastro-header h1 span {
            color: var(--accent);
        }

        .cadastro-header p {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .form-group label .required {
            color: var(--danger);
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.95rem;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-cadastrar {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cadastrar:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(232, 133, 12, 0.3);
        }

        .btn-cadastrar:active {
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

        .success-message {
            background: #d1fae5;
            color: var(--success);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-left: 4px solid var(--success);
            display: <?php echo $sucesso ? 'block' : 'none'; ?>;
        }

        .cadastro-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.9rem;
            color: var(--gray-500);
        }

        .cadastro-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .cadastro-footer a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
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

        @media (max-width: 480px) {
            .cadastro-container {
                padding: 32px 24px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Máscara de CPF e Telefone */
        .mask-cpf {
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-header">
            <div class="logo">🛠️</div>
            <h1>Criar Conta</h1>
            <p>Cadastre-se para acompanhar seus pedidos</p>
        </div>

        <?php if ($erro): ?>
        <div class="error-message"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($sucesso); ?>
            <br>
            <a href="index.php" style="color: var(--success); font-weight: 700;">Clique aqui para fazer login</a>
        </div>
        <?php endif; ?>

        <?php if (!$sucesso): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome completo <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="nome" 
                    name="nome" 
                    placeholder="Seu nome completo"
                    value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="seu@email.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input 
                        type="text" 
                        id="cpf" 
                        name="cpf" 
                        placeholder="000.000.000-00"
                        class="mask-cpf"
                        maxlength="14"
                        value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>"
                        oninput="mascararCPF(this)"
                    >
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input 
                        type="text" 
                        id="telefone" 
                        name="telefone" 
                        placeholder="(00) 00000-0000"
                        maxlength="15"
                        value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>"
                        oninput="mascararTelefone(this)"
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="senha">Senha <span class="required">*</span></label>
                <div class="password-toggle">
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        placeholder="Mínimo 6 caracteres"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword('senha')">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha <span class="required">*</span></label>
                <div class="password-toggle">
                    <input 
                        type="password" 
                        id="confirmar_senha" 
                        name="confirmar_senha" 
                        placeholder="Digite novamente"
                        required
                    >
                    <button type="button" class="toggle-btn" onclick="togglePassword('confirmar_senha')">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-cadastrar">📝 Criar Conta</button>
        </form>
        <?php endif; ?>

        <div class="cadastro-footer">
            <?php if ($sucesso): ?>
                <a href="index.php">← Voltar para o login</a>
            <?php else: ?>
                Já tem uma conta? <a href="index.php">Faça login</a>
            <?php endif; ?>
            <br>
            <a href="../index.php" class="voltar-loja">← Voltar para a loja</a>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const btn = input.parentElement.querySelector('.toggle-btn');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        function mascararCPF(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                input.value = value;
            }
        }

        function mascararTelefone(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                input.value = value;
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