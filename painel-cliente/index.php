<?php
// painel-cliente/index.php
session_start();
require_once '../admin/function/conexao.php';


// Verificar se está logado
if (!isset($_SESSION['cliente_id']) || !isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../login/index.php');
    exit();
}

$cliente_id = $_SESSION['cliente_id'];
$cliente_nome = $_SESSION['cliente_nome'];
$cliente_email = $_SESSION['cliente_email'];

// Buscar dados do cliente
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch();

// Buscar pedidos do cliente
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY data_pedido DESC");
$stmt->execute([$cliente_id]);
$pedidos = $stmt->fetchAll();

// Buscar endereços do cliente
$stmt = $pdo->prepare("SELECT * FROM enderecos WHERE id_usuario = ?");
$stmt->execute([$cliente_id]);
$enderecos = $stmt->fetchAll();

// Contar itens no carrinho
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM carrinhos WHERE id_usuario = ? AND status = 'ativo'");
$stmt->execute([$cliente_id]);
$carrinho_count = $stmt->fetch()['total'] ?? 0;

// Total gasto pelo cliente
$stmt = $pdo->prepare("SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE id_usuario = ? AND status_pedido IN ('entregue', 'pago', 'processando', 'enviado')");
$stmt->execute([$cliente_id]);
$total_gasto = $stmt->fetch()['total'] ?? 0;

// Último pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY data_pedido DESC LIMIT 1");
$stmt->execute([$cliente_id]);
$ultimo_pedido = $stmt->fetch();

// Pedidos pendentes (não entregues)
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pedidos WHERE id_usuario = ? AND status_pedido NOT IN ('entregue', 'cancelado')");
$stmt->execute([$cliente_id]);
$pedidos_pendentes = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>👤 Minha Conta - Ferragem Ponto 7</title>
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon" />
    <style>
        /* ===== RESET E VARIÁVEIS ===== */
        :root {
            --primary: #0f3b3f;
            --primary-dark: #0a2a2d;
            --primary-light: #d4edf0;
            --accent: #e8850c;
            --accent-hover: #d0750a;
            --dark: #1a1a2e;
            --gray-50: #f7f8fa;
            --gray-100: #edf0f3;
            --gray-200: #dce1e8;
            --gray-300: #bcc3cd;
            --gray-500: #7a8599;
            --gray-700: #3d4552;
            --white: #ffffff;
            --success: #2d9b7a;
            --success-dark: #217a5f;
            --danger: #d94a5a;
            --warning: #f59e0b;
            --shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --radius-sm: 8px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: var(--primary);
            color: var(--white);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
            border-bottom: 3px solid var(--accent);
        }

        .header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header .logo img {
            height: 45px;
            width: auto;
        }

        .header .logo h1 {
            font-size: 1.3rem;
            font-weight: 800;
        }

        .header .logo h1 span {
            color: var(--accent);
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--white);
        }

        .header .user-info .user-details {
            display: flex;
            flex-direction: column;
        }

        .header .user-info .name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .header .user-info .email {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-header {
            background: rgba(255, 255, 255, 0.12);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-header.btn-loja {
            background: var(--accent);
        }

        .btn-header.btn-loja:hover {
            background: var(--accent-hover);
        }

        .btn-header.btn-sair:hover {
            background: var(--danger);
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 24px;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-bottom: 24px;
        }

        .breadcrumb a {
            color: var(--accent);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* ===== CARDS DE ESTATÍSTICAS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
            transition: all var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
            margin: 2px 0;
        }

        .stat-card:nth-child(1) { border-left-color: var(--accent); }
        .stat-card:nth-child(2) { border-left-color: var(--success); }
        .stat-card:nth-child(3) { border-left-color: var(--warning); }
        .stat-card:nth-child(4) { border-left-color: var(--primary); }

        /* ===== TABELAS ===== */
        .table-container {
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 32px;
            overflow-x: auto;
        }

        .table-container .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-container .table-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-container .table-header .badge-carrinho {
            background: var(--accent);
            color: var(--white);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition);
        }

        .table-container .table-header .badge-carrinho:hover {
            background: var(--accent-hover);
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: var(--gray-50);
        }

        table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--gray-500);
            border-bottom: 2px solid var(--gray-200);
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.9rem;
        }

        table tbody tr:hover {
            background: var(--gray-50);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-badge.paid {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-badge.processing {
            background: #e0e7ff;
            color: #3730a3;
        }
        .status-badge.shipped {
            background: #e0e7ff;
            color: #3730a3;
        }
        .status-badge.delivered {
            background: #d1fae5;
            color: var(--success-dark);
        }
        .status-badge.cancelled {
            background: #f3f4f6;
            color: var(--gray-500);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-500);
        }

        .empty-state .icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state h4 {
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .empty-state p {
            font-size: 0.9rem;
        }

        .btn-action {
            display: inline-block;
            background: var(--primary);
            color: var(--white);
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition);
            margin-top: 12px;
            border: none;
            cursor: pointer;
        }

        .btn-action:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 59, 63, 0.3);
        }

        .btn-action.btn-accent {
            background: var(--accent);
        }

        .btn-action.btn-accent:hover {
            background: var(--accent-hover);
            box-shadow: 0 4px 12px rgba(232, 133, 12, 0.3);
        }

        .btn-action.btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-action.btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* ===== ENDEREÇOS ===== */
        .enderecos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .endereco-card {
            background: var(--gray-50);
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            border-left: 4px solid var(--gray-300);
            transition: all var(--transition);
        }

        .endereco-card:hover {
            border-left-color: var(--accent);
            background: var(--white);
            box-shadow: var(--shadow);
        }

        .endereco-card .principal {
            background: var(--success);
            color: var(--white);
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 6px;
        }

        .endereco-card .endereco-texto {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .header {
                padding: 12px 16px;
            }
            .header .logo h1 {
                font-size: 1rem;
            }
            .header .logo img {
                height: 35px;
            }
            .header .user-info .email {
                display: none;
            }
            .header .user-info .user-details {
                display: none;
            }
            .container {
                padding: 0 12px;
                margin: 16px auto;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            .stat-card {
                padding: 14px 16px;
            }
            .stat-card .stat-value {
                font-size: 1.3rem;
            }
            .table-container {
                padding: 16px;
            }
            .btn-header span {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .enderecos-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header class="header">
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit;">
                <img src="img/logo.png" alt="Ferragem Ponto 7" />
                <h1>Ferragem <span>Ponto 7</span></h1>
            </a>
        </div>
        <div class="user-info">
            <div class="avatar"><?php echo strtoupper(substr($cliente_nome, 0, 1)); ?></div>
            <div class="user-details">
                <div class="name">Olá, <?php echo htmlspecialchars($cliente_nome); ?></div>
                <div class="email"><?php echo htmlspecialchars($cliente_email); ?></div>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn-header btn-loja">
                    <ion-icon name="storefront-outline"></ion-icon>
                    <span>Loja</span>
                </a>
                <a href="carrinho/index.php" class="btn-header" style="background: rgba(255,255,255,0.08);">
                    <ion-icon name="cart-outline"></ion-icon>
                    <span><?php echo $carrinho_count; ?></span>
                </a>
                <a href="login/logout.php" class="btn-header btn-sair" onclick="return confirm('Tem certeza que deseja sair?')">
                    <ion-icon name="log-out-outline"></ion-icon>
                    <span>Sair</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ===== CONTEÚDO ===== -->
    <div class="container">

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">🏠 Home</a> &gt; <span>Minha Conta</span>
        </div>

        <!-- ===== STATS ===== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-label">Total de Pedidos</div>
                <div class="stat-value"><?php echo count($pedidos); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🛒</div>
                <div class="stat-label">Itens no Carrinho</div>
                <div class="stat-value"><?php echo $carrinho_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Total Gasto</div>
                <div class="stat-value">R$ <?php echo number_format($total_gasto, 2, ',', '.'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">Pedidos Pendentes</div>
                <div class="stat-value"><?php echo $pedidos_pendentes; ?></div>
            </div>
        </div>

        <!-- ===== CARRINHO ===== -->
        <div class="table-container">
            <div class="table-header">
                <h3>🛒 Meu Carrinho</h3>
                <a href="carrinho/index.php" class="badge-carrinho">Ver Carrinho →</a>
            </div>
            <?php if ($carrinho_count > 0): ?>
                <p style="color: var(--gray-500);">
                    Você tem <strong><?php echo $carrinho_count; ?></strong> item(ns) no carrinho.
                    <a href="carrinho/index.php" style="color: var(--accent); font-weight: 600;">Clique aqui para finalizar</a>
                </p>
                <br>
                <a href="carrinho/index.php" class="btn-action btn-accent">🛒 Ir para o Carrinho</a>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">🛒</span>
                    <h4>Seu carrinho está vazio</h4>
                    <p>Explore nossa loja e encontre os melhores produtos para sua construção</p>
                    <a href="index.php" class="btn-action btn-accent">🛍️ Comprar agora</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== PEDIDOS ===== -->
        <div class="table-container">
            <div class="table-header">
                <h3>📋 Meus Pedidos</h3>
            </div>
            <?php if (count($pedidos) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php
                            $statusMap = [
                                'pendente' => 'pending',
                                'pago' => 'paid',
                                'processando' => 'processing',
                                'enviado' => 'shipped',
                                'entregue' => 'delivered',
                                'cancelado' => 'cancelled'
                            ];
                            $statusClass = $statusMap[$pedido['status_pedido']] ?? 'pending';
                            $statusLabel = [
                                'pendente' => 'Pendente',
                                'pago' => 'Pago',
                                'processando' => 'Processando',
                                'enviado' => 'Enviado',
                                'entregue' => 'Entregue',
                                'cancelado' => 'Cancelado'
                            ][$pedido['status_pedido']] ?? $pedido['status_pedido'];
                            ?>
                            <tr>
                                <td><strong>#<?php echo str_pad($pedido['id_pedido'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                                <td><strong>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></strong></td>
                                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">📋</span>
                    <h4>Nenhum pedido encontrado</h4>
                    <p>Você ainda não fez nenhum pedido. Comece agora mesmo!</p>
                    <a href="../index.php" class="btn-action btn-accent">🛍️ Fazer primeiro pedido</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== ENDEREÇOS ===== -->
        <div class="table-container">
            <div class="table-header">
                <h3>📌 Meus Endereços</h3>
                <a href="#" class="badge-carrinho" onclick="alert('🔜 Em breve você poderá adicionar novos endereços')">+ Adicionar</a>
            </div>
            <?php if (count($enderecos) > 0): ?>
                <div class="enderecos-grid">
                    <?php foreach ($enderecos as $endereco): ?>
                        <div class="endereco-card">
                            <div class="endereco-texto">
                                <strong><?php echo htmlspecialchars($endereco['logradouro']); ?>, <?php echo $endereco['numero']; ?></strong>
                                <br>
                                <?php if (!empty($endereco['complemento'])): ?>
                                    <?php echo htmlspecialchars($endereco['complemento']); ?><br>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($endereco['bairro']); ?>, <?php echo htmlspecialchars($endereco['cidade']); ?>/<?php echo $endereco['estado']; ?>
                                <br>
                                CEP: <?php echo $endereco['cep']; ?>
                                <?php if ($endereco['principal']): ?>
                                    <br><span class="principal">⭐ Principal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">📌</span>
                    <h4>Nenhum endereço cadastrado</h4>
                    <p>Adicione um endereço para agilizar seus pedidos</p>
                    <button class="btn-action btn-accent" onclick="alert('🔜 Em breve você poderá adicionar endereços')">➕ Adicionar endereço</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== DADOS DO PERFIL ===== -->
        <div class="table-container">
            <div class="table-header">
                <h3>👤 Meus Dados</h3>
                <button class="badge-carrinho" onclick="alert('🔜 Em breve você poderá editar seus dados')">✏️ Editar</button>
            </div>
            <table>
                <tbody>
                    <tr>
                        <td style="font-weight: 600; width: 140px;">Nome</td>
                        <td><?php echo htmlspecialchars($cliente['nome_completo']); ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Email</td>
                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">CPF</td>
                        <td><?php echo $cliente['cpf'] ? htmlspecialchars($cliente['cpf']) : 'Não informado'; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Telefone</td>
                        <td><?php echo $cliente['telefone'] ? htmlspecialchars($cliente['telefone']) : 'Não informado'; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Cadastro</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($cliente['data_cadastro'])); ?></td>
                    </tr>
                    <?php if ($cliente['ultimo_login']): ?>
                    <tr>
                        <td style="font-weight: 600;">Último acesso</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($cliente['ultimo_login'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- ===== FOOTER ===== -->
    <footer style="background: var(--primary); color: var(--white); padding: 24px 0; margin-top: 40px;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px; text-align: center;">
            <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem;">
                &copy; 2026 <a href="#" style="color: var(--accent); text-decoration: none;">Ferragem Ponto 7</a> - Todos os direitos reservados
            </p>
            <p style="color: rgba(255,255,255,0.4); font-size: 0.75rem; margin-top: 4px;">
                Sua construção em boas mãos
            </p>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>