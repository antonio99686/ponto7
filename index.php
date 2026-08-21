<?php
// painel-cliente/index.php
session_start();

require_once 'admin/function/conexao.php';


// Verificar se está logado
if (!isset($_SESSION['cliente_id']) || !isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login/index.php');
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👤 Meu Painel - Construmix</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
        }

        .header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
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

        .header .user-info .name {
            font-weight: 600;
        }

        .header .user-info .email {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
        }

        .btn-sair {
            background: rgba(255,255,255,0.15);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition);
        }

        .btn-sair:hover {
            background: rgba(255,255,255,0.25);
        }

        .btn-loja {
            background: var(--accent);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition);
        }

        .btn-loja:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 24px;
        }

        /* ===== CARDS DE ESTATÍSTICAS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
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

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin: 4px 0 2px;
        }

        .stat-card:nth-child(2) { border-left-color: var(--accent); }
        .stat-card:nth-child(3) { border-left-color: var(--success); }

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
        }

        .table-container .table-header .badge-carrinho {
            background: var(--accent);
            color: var(--white);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
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
            font-size: 0.8rem;
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
            font-size: 0.75rem;
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

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .header {
                padding: 12px 16px;
            }
            .header .logo h1 {
                font-size: 1rem;
            }
            .header .user-info .email {
                display: none;
            }
            .container {
                padding: 0 12px;
                margin: 16px auto;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .table-container {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- ===== HEADER ===== -->
    <header class="header">
        <div class="logo">
            <span style="font-size: 1.5rem;">🛠️</span>
            <h1>Construmix</h1>
        </div>
        <div class="user-info">
            <div class="avatar"><?php echo strtoupper(substr($cliente_nome, 0, 1)); ?></div>
            <div>
                <div class="name"><?php echo htmlspecialchars($cliente_nome); ?></div>
                <div class="email"><?php echo htmlspecialchars($cliente_email); ?></div>
            </div>
            <a href="../index.php" class="btn-loja">🏪 Loja</a>
            <button class="btn-sair" onclick="logout()">Sair</button>
        </div>
    </header>

    <!-- ===== CONTEÚDO ===== -->
    <div class="container">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">📦 Total de Pedidos</div>
                <div class="stat-value"><?php echo count($pedidos); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">🛒 Itens no Carrinho</div>
                <div class="stat-value"><?php echo $carrinho_count; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">📌 Endereços Cadastrados</div>
                <div class="stat-value"><?php echo count($enderecos); ?></div>
            </div>
        </div>

        <!-- Carrinho -->
        <div class="table-container">
            <div class="table-header">
                <h3>🛒 Meu Carrinho</h3>
                <a href="../carrinho/index.php" class="badge-carrinho">Ver Carrinho →</a>
            </div>
            <?php if ($carrinho_count > 0): ?>
                <p style="color: var(--gray-500);">Você tem <strong><?php echo $carrinho_count; ?></strong> item(ns) no carrinho.</p>
                <br>
                <a href="../carrinho/index.php" class="btn-loja" style="display: inline-block;">Ver carrinho</a>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">🛒</span>
                    <h4>Seu carrinho está vazio</h4>
                    <p>Explore nossa loja e adicione produtos</p>
                    <br>
                    <a href="../index.php" class="btn-loja" style="display: inline-block;">🛍️ Comprar agora</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pedidos -->
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
                                'processando' => 'shipped',
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
                                <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">📋</span>
                    <h4>Nenhum pedido encontrado</h4>
                    <p>Faça seu primeiro pedido na nossa loja</p>
                    <br>
                    <a href="../index.php" class="btn-loja" style="display: inline-block;">🛍️ Comprar agora</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Endereços -->
        <div class="table-container">
            <div class="table-header">
                <h3>📌 Meus Endereços</h3>
            </div>
            <?php if (count($enderecos) > 0): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px;">
                    <?php foreach ($enderecos as $endereco): ?>
                        <div style="background: var(--gray-50); padding: 16px; border-radius: var(--radius-sm);">
                            <strong><?php echo htmlspecialchars($endereco['logradouro']); ?>, <?php echo $endereco['numero']; ?></strong>
                            <br>
                            <?php echo htmlspecialchars($endereco['bairro']); ?>, <?php echo htmlspecialchars($endereco['cidade']); ?>/<?php echo $endereco['estado']; ?>
                            <br>
                            CEP: <?php echo $endereco['cep']; ?>
                            <?php if ($endereco['principal']): ?>
                                <br>
                                <span style="background: var(--success); color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.7rem; display: inline-block; margin-top: 4px;">Principal</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="icon">📌</span>
                    <h4>Nenhum endereço cadastrado</h4>
                    <p>Adicione um endereço para agilizar seus pedidos</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function logout() {
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'login/logout.php';
            }
        }
    </script>
</body>
</html>