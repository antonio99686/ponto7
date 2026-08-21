<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Ferragem Ponto 7 - Painel Administrativo</title>
   <!-- Redirecionar se não estiver logado -->
    <script>
        // Verificação de sessão via fetch
        (async function() {
            try {
                const response = await fetch('check_session.php');
                const data = await response.json();
                
                if (!data.logado) {
                    window.location.href = '../login/index.php';
                }
            } catch (error) {
                console.error('Erro ao verificar sessão:', error);
                window.location.href = '../login/index.php';
            }
        })();
    </script>
  <style>
    /* ============================================================
           ESTILOS DO PAINEL ADMINISTRATIVO
           ============================================================ */

    /* ===== RESET E VARIÁVEIS ===== */
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
      --gray-300: #bcc3cd;
      --gray-500: #7a8599;
      --gray-700: #3d4552;
      --white: #ffffff;
      --success: #2d9b7a;
      --success-dark: #217a5f;
      --danger: #d94a5a;
      --warning: #f59e0b;
      --info: #3b82f6;
      --shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.1);
      --radius: 16px;
      --radius-sm: 8px;
      --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      --sidebar-width: 240px;
      --header-height: 70px;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: var(--gray-50);
      color: var(--dark);
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
    }

    /* ============================================================
           SIDEBAR
           ============================================================ */
    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      background: var(--primary);
      color: var(--white);
      position: fixed;
      left: 0;
      top: 0;
      overflow-y: auto;
      z-index: 100;
      transition: transform var(--transition);
      display: flex;
      flex-direction: column;
    }

    .sidebar::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 10px;
    }

    .sidebar-brand {
      padding: 24px 20px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sidebar-brand .logo-icon {
      font-size: 2rem;
    }

    .sidebar-brand h2 {
      font-size: 1.3rem;
      font-weight: 800;
    }

    .sidebar-brand h2 span {
      color: var(--accent);
    }

    .sidebar-menu {
      flex: 1;
      padding: 20px 12px;
    }

    .sidebar-menu .menu-label {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: rgba(255, 255, 255, 0.4);
      padding: 12px 12px 8px;
      font-weight: 600;
    }

    .sidebar-menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      border-radius: var(--radius-sm);
      transition: all var(--transition);
      font-weight: 500;
      font-size: 0.9rem;
      cursor: pointer;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
    }

    .sidebar-menu a:hover {
      background: rgba(255, 255, 255, 0.08);
      color: var(--white);
    }

    .sidebar-menu a.active {
      background: var(--accent);
      color: var(--white);
      box-shadow: 0 4px 12px rgba(232, 133, 12, 0.3);
    }

    .sidebar-menu a .icon {
      font-size: 1.2rem;
      width: 24px;
      text-align: center;
    }

    .sidebar-menu a .badge {
      margin-left: auto;
      background: var(--danger);
      color: white;
      font-size: 0.7rem;
      padding: 2px 8px;
      border-radius: 12px;
      font-weight: 600;
    }

    .sidebar-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-footer .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sidebar-footer .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1rem;
      color: var(--white);
    }

    .sidebar-footer .user-name {
      font-weight: 600;
      font-size: 0.9rem;
    }

    .sidebar-footer .user-role {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.5);
    }

    /* ============================================================
           MAIN CONTENT
           ============================================================ */
    .main-content {
      margin-left: var(--sidebar-width);
      flex: 1;
      min-height: 100vh;
    }

    /* ===== HEADER ===== */
    .top-header {
      height: var(--header-height);
      background: var(--white);
      border-bottom: 1px solid var(--gray-200);
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: var(--shadow);
    }

    .top-header .page-title {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--dark);
    }

    .top-header .page-title small {
      font-weight: 400;
      color: var(--gray-500);
      font-size: 0.85rem;
    }

    .top-header .header-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .top-header .header-actions .btn-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: none;
      background: var(--gray-100);
      cursor: pointer;
      font-size: 1.1rem;
      transition: all var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .top-header .header-actions .btn-icon:hover {
      background: var(--gray-200);
    }

    .top-header .header-actions .btn-icon .notif-dot {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 8px;
      height: 8px;
      background: var(--danger);
      border-radius: 50%;
      border: 2px solid var(--white);
    }

    .hamburger {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--dark);
    }

    /* ============================================================
           DASHBOARD CONTENT
           ============================================================ */
    .dashboard-content {
      padding: 32px;
    }

    /* ===== CARDS ===== */
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

    .stat-card .stat-change {
      font-size: 0.8rem;
      font-weight: 600;
    }

    .stat-card .stat-change.positive {
      color: var(--success);
    }

    .stat-card .stat-change.negative {
      color: var(--danger);
    }

    .stat-card:nth-child(2) {
      border-left-color: var(--accent);
    }

    .stat-card:nth-child(3) {
      border-left-color: var(--success);
    }

    .stat-card:nth-child(4) {
      border-left-color: var(--warning);
    }

    .stat-card:nth-child(5) {
      border-left-color: var(--info);
    }

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

    .table-container .table-header .actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: var(--radius-sm);
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-primary {
      background: var(--primary);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
    }

    .btn-accent {
      background: var(--accent);
      color: var(--white);
    }

    .btn-accent:hover {
      background: var(--accent-hover);
    }

    .btn-success {
      background: var(--success);
      color: var(--white);
    }

    .btn-success:hover {
      background: var(--success-dark);
    }

    .btn-danger {
      background: var(--danger);
      color: var(--white);
    }

    .btn-danger:hover {
      background: #c0392b;
    }

    .btn-outline {
      background: transparent;
      color: var(--gray-700);
      border: 1px solid var(--gray-200);
    }

    .btn-outline:hover {
      background: var(--gray-100);
    }

    .btn-sm {
      padding: 4px 10px;
      font-size: 0.75rem;
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

    .status-badge.active {
      background: #d1fae5;
      color: var(--success-dark);
    }

    .status-badge.inactive {
      background: #fee2e2;
      color: var(--danger);
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

    .product-thumb {
      width: 40px;
      height: 40px;
      border-radius: var(--radius-sm);
      background: var(--gray-100);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    /* ============================================================
           MÓDULOS (seções)
           ============================================================ */
    .module {
      display: none;
    }

    .module.active {
      display: block;
    }

    /* ============================================================
           FORMULÁRIOS
           ============================================================ */
    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      font-size: 0.85rem;
      margin-bottom: 4px;
      color: var(--gray-700);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
      transition: border var(--transition);
      font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(15, 59, 63, 0.1);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    @media (max-width: 600px) {
      .form-row {
        grid-template-columns: 1fr;
      }
    }

    /* ============================================================
           MODAL
           ============================================================ */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 200;
      align-items: center;
      justify-content: center;
      padding: 20px;
      backdrop-filter: blur(4px);
    }

    .modal-overlay.show {
      display: flex;
    }

    .modal {
      background: var(--white);
      border-radius: var(--radius);
      padding: 32px;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: var(--shadow-lg);
      animation: modalSlide 0.3s ease;
    }

    @keyframes modalSlide {
      from {
        transform: translateY(-30px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .modal .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--gray-200);
    }

    .modal .modal-header h3 {
      font-size: 1.2rem;
    }

    .modal .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--gray-500);
      transition: color var(--transition);
    }

    .modal .modal-close:hover {
      color: var(--danger);
    }

    .modal .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 20px;
      justify-content: flex-end;
    }

    /* ============================================================
           RESPONSIVIDADE
           ============================================================ */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .hamburger {
        display: block;
      }

      .top-header {
        padding: 0 16px;
      }

      .dashboard-content {
        padding: 16px;
      }

      .stats-grid {
        grid-template-columns: 1fr 1fr;
      }

      .top-header .page-title small {
        display: none;
      }
    }

    @media (max-width: 480px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .table-container {
        padding: 16px;
      }

      .modal {
        padding: 20px;
        margin: 10px;
      }
    }

    /* ============================================================
           TOAST NOTIFICATION
           ============================================================ */
    .toast-container {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .toast {
      background: var(--dark);
      color: var(--white);
      padding: 14px 24px;
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      font-weight: 500;
      transform: translateX(120%);
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border-left: 4px solid var(--accent);
      min-width: 280px;
    }

    .toast.show {
      transform: translateX(0);
      opacity: 1;
    }

    .toast.success {
      border-left-color: var(--success);
    }

    .toast.error {
      border-left-color: var(--danger);
    }

    .toast.warning {
      border-left-color: var(--warning);
    }

    /* ============================================================
           ANIMAÇÕES
           ============================================================ */
    .fade-in {
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ============================================================
           LOADING SPINNER
           ============================================================ */
    .loading {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      color: var(--gray-500);
    }

    .loading::after {
      content: '';
      width: 24px;
      height: 24px;
      border: 3px solid var(--gray-200);
      border-top-color: var(--primary);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin-left: 12px;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
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

    /* ============================================================
           CHECKBOX SWITCH
           ============================================================ */
    .switch {
      position: relative;
      display: inline-block;
      width: 44px;
      height: 24px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .switch .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gray-300);
      transition: var(--transition);
      border-radius: 24px;
    }

    .switch .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background: var(--white);
      transition: var(--transition);
      border-radius: 50%;
    }

    .switch input:checked+.slider {
      background: var(--success);
    }

    .switch input:checked+.slider:before {
      transform: translateX(20px);
    }
  </style>
</head>

<body>
  <!-- ============================================================
    SIDEBAR
    ============================================================ -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <span class="logo-icon">
        <img src="../img/logo.png" alt="image" width="350px" height="200px" style="position: relative; right: 20%;" /></span>
    </div>

    <nav class="sidebar-menu">
      <div class="menu-label">Menu Principal</div>
      <a href="#" class="active" data-module="dashboard">
        <span class="icon">📊</span> Dashboard
      </a>
      <a href="#" data-module="produtos">
        <span class="icon">📦</span> Produtos
        <span class="badge" id="badge-produtos">0</span>
      </a>
      <a href="#" data-module="pedidos">
        <span class="icon">📋</span> Pedidos
        <span class="badge" id="badge-pedidos">0</span>
      </a>
      <a href="#" data-module="clientes">
        <span class="icon">👤</span> Clientes
      </a>
      <a href="#" data-module="categorias">
        <span class="icon">📂</span> Categorias
      </a>

      <div class="menu-label" style="margin-top: 16px;">Configurações</div>
      <a href="#" data-module="cupons">
        <span class="icon">🎫</span> Cupons
      </a>
      <a href="#" data-module="configuracoes">
        <span class="icon">⚙️</span> Configurações
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-info">
        <div class="user-avatar">A</div>
        <div>
          <div class="user-name" id="user-name">Administrador</div>
          <div class="user-role" id="user-email">admin@construmix.com</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ============================================================
    MAIN CONTENT
    ============================================================ -->
  <div class="main-content">
    <!-- Top Header -->
    <header class="top-header">
      <div style="display: flex; align-items: center; gap: 12px;">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <div class="page-title">
          Dashboard <small>Visão geral da loja</small>
        </div>
      </div>
      <div class="header-actions">
        <button class="btn-icon" title="Notificações" onclick="showToast('🔔 Você tem 3 notificações')">
          🔔
          <span class="notif-dot"></span>
        </button>
        <button class="btn-icon" title="Sair" onclick="logout()">🚪</button>
      </div>
    </header>

    <!-- Content -->
    <div class="dashboard-content">

      <!-- ============================================================
            MÓDULO: DASHBOARD
            ============================================================ -->
      <div class="module active" id="mod-dashboard">
        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-label">Total de Produtos</div>
            <div class="stat-value" id="stat-produtos">--</div>
            <div class="stat-change positive">↑ Carregando...</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Pedidos Hoje</div>
            <div class="stat-value" id="stat-pedidos">--</div>
            <div class="stat-change positive">↑ Carregando...</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Faturamento Mensal</div>
            <div class="stat-value" id="stat-faturamento">R$ --</div>
            <div class="stat-change positive">↑ Carregando...</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Clientes Ativos</div>
            <div class="stat-value" id="stat-clientes">--</div>
            <div class="stat-change positive">↑ Carregando...</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Produtos em Falta</div>
            <div class="stat-value" id="stat-falta">--</div>
            <div class="stat-change negative">⚠️ Verificar estoque</div>
          </div>
        </div>

        <!-- Últimos Pedidos -->
        <div class="table-container fade-in">
          <div class="table-header">
            <h3>📋 Últimos Pedidos</h3>
            <div class="actions">
              <button class="btn btn-primary btn-sm" onclick="showModule('pedidos')">Ver Todos</button>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Data</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="ultimos-pedidos">
              <tr>
                <td colspan="5" style="text-align: center; color: var(--gray-500);">Carregando pedidos...</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Produtos Mais Vendidos -->
        <div class="table-container fade-in">
          <div class="table-header">
            <h3>🏆 Produtos Mais Vendidos</h3>
          </div>
          <table>
            <thead>
              <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Vendas</th>
                <th>Receita</th>
              </tr>
            </thead>
            <tbody id="mais-vendidos">
              <tr>
                <td colspan="4" style="text-align: center; color: var(--gray-500);">Carregando dados...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: PRODUTOS
            ============================================================ -->
      <div class="module" id="mod-produtos">
        <div class="table-container">
          <div class="table-header">
            <h3>📦 Gerenciar Produtos</h3>
            <div class="actions">
              <button class="btn btn-accent" onclick="abrirModalProduto()">➕ Novo Produto</button>
            </div>
          </div>
          <div id="produtos-loading" class="loading">Carregando produtos</div>
          <div id="produtos-content" style="display: none;">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Imagem</th>
                  <th>Nome</th>
                  <th>SKU</th>
                  <th>Preço</th>
                  <th>Estoque</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="tabela-produtos"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: PEDIDOS
            ============================================================ -->
      <div class="module" id="mod-pedidos">
        <div class="table-container">
          <div class="table-header">
            <h3>📋 Gerenciar Pedidos</h3>
            <div class="actions">
              <select id="filtro-status-pedido" class="btn btn-outline btn-sm" style="padding: 6px 12px;" onchange="loadPedidos()">
                <option value="">Todos os status</option>
                <option value="pendente">Pendente</option>
                <option value="pago">Pago</option>
                <option value="processando">Processando</option>
                <option value="enviado">Enviado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>
          </div>
          <div id="pedidos-loading" class="loading">Carregando pedidos</div>
          <div id="pedidos-content" style="display: none;">
            <table>
              <thead>
                <tr>
                  <th>Pedido</th>
                  <th>Cliente</th>
                  <th>Itens</th>
                  <th>Total</th>
                  <th>Data</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="tabela-pedidos"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: CLIENTES
            ============================================================ -->
      <div class="module" id="mod-clientes">
        <div class="table-container">
          <div class="table-header">
            <h3>👤 Gerenciar Clientes</h3>
            <div class="actions">
              <button class="btn btn-accent" onclick="showToast('🔜 Funcionalidade em desenvolvimento')">➕ Novo Cliente</button>
            </div>
          </div>
          <div id="clientes-loading" class="loading">Carregando clientes</div>
          <div id="clientes-content" style="display: none;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>CPF</th>
                  <th>Status</th>
                  <th>Cadastro</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="tabela-clientes"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: CATEGORIAS
            ============================================================ -->
      <div class="module" id="mod-categorias">
        <div class="table-container">
          <div class="table-header">
            <h3>📂 Gerenciar Categorias</h3>
            <div class="actions">
              <button class="btn btn-accent" onclick="abrirModalCategoria()">➕ Nova Categoria</button>
            </div>
          </div>
          <div id="categorias-loading" class="loading">Carregando categorias</div>
          <div id="categorias-content" style="display: none;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Ícone</th>
                  <th>Nome</th>
                  <th>Slug</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="tabela-categorias"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: CUPONS
            ============================================================ -->
      <div class="module" id="mod-cupons">
        <div class="table-container">
          <div class="table-header">
            <h3>🎫 Gerenciar Cupons</h3>
            <div class="actions">
              <button class="btn btn-accent" onclick="abrirModalCupom()">➕ Novo Cupom</button>
            </div>
          </div>
          <div id="cupons-loading" class="loading">Carregando cupons</div>
          <div id="cupons-content" style="display: none;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Código</th>
                  <th>Desconto</th>
                  <th>Válido</th>
                  <th>Usos</th>
                  <th>Status</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody id="tabela-cupons"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ============================================================
            MÓDULO: CONFIGURAÇÕES
            ============================================================ -->
      <div class="module" id="mod-configuracoes">
        <div class="table-container">
          <div class="table-header">
            <h3>⚙️ Configurações do Sistema</h3>
          </div>
          <div style="padding: 20px 0;">
            <div class="form-row">
              <div class="form-group">
                <label>Nome da Loja</label>
                <input type="text" value="Construmix" id="config-nome-loja">
              </div>
              <div class="form-group">
                <label>Email de Contato</label>
                <input type="email" value="contato@construmix.com" id="config-email-contato">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Telefone</label>
                <input type="text" value="(11) 99999-9999" id="config-telefone">
              </div>
              <div class="form-group">
                <label>Moeda Padrão</label>
                <select id="config-moeda">
                  <option value="BRL">Real (R$)</option>
                  <option value="USD">Dólar (US$)</option>
                  <option value="EUR">Euro (€)</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Frete Padrão</label>
                <input type="number" step="0.01" value="15.00" id="config-frete">
              </div>
              <div class="form-group">
                <label>Status da Loja</label>
                <select id="config-status-loja">
                  <option value="ativa">Ativa</option>
                  <option value="manutencao">Em Manutenção</option>
                  <option value="offline">Offline</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label style="display: flex; align-items: center; gap: 10px;">
                <span>Manter estoque negativo?</span>
                <label class="switch">
                  <input type="checkbox" checked id="config-estoque-negativo">
                  <span class="slider"></span>
                </label>
              </label>
            </div>
            <button class="btn btn-primary" onclick="salvarConfiguracoes()">💾 Salvar Configurações</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================================
    MODAL PRODUTO
    ============================================================ -->
  <div class="modal-overlay" id="modal-produto">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modal-titulo">Novo Produto</h3>
        <button class="modal-close" onclick="fecharModal()">✕</button>
      </div>
      <form id="form-produto" onsubmit="salvarProduto(); return false;">
        <input type="hidden" id="produto-id">

        <div class="form-group">
          <label>Nome do Produto *</label>
          <input type="text" id="produto-nome" required placeholder="Ex: Martelo Unha 29mm">
        </div>

        <div class="form-group">
          <label>Descrição</label>
          <textarea id="produto-descricao" rows="3" placeholder="Descrição detalhada do produto"></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>SKU (Código)</label>
            <input type="text" id="produto-sku" placeholder="Ex: FER-001">
          </div>
          <div class="form-group">
            <label>Preço de Venda *</label>
            <input type="number" step="0.01" id="produto-preco" required placeholder="0.00">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Estoque Atual</label>
            <input type="number" id="produto-estoque" value="0">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select id="produto-status">
              <option value="ativo">Ativo</option>
              <option value="inativo">Inativo</option>
              <option value="rascunho">Rascunho</option>
              <option value="esgotado">Esgotado</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px;">
              <span>Produto em Destaque</span>
              <label class="switch">
                <input type="checkbox" id="produto-destaque">
                <span class="slider"></span>
              </label>
            </label>
          </div>
          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px;">
              <span>Produto Novo</span>
              <label class="switch">
                <input type="checkbox" id="produto-novo">
                <span class="slider"></span>
              </label>
            </label>
          </div>
        </div>

        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="fecharModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">💾 Salvar Produto</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================
    MODAL CATEGORIA
    ============================================================ -->
  <div class="modal-overlay" id="modal-categoria">
    <div class="modal">
      <div class="modal-header">
        <h3>Nova Categoria</h3>
        <button class="modal-close" onclick="fecharModalCategoria()">✕</button>
      </div>
      <form id="form-categoria" onsubmit="salvarCategoria(); return false;">
        <div class="form-group">
          <label>Nome da Categoria *</label>
          <input type="text" id="categoria-nome" required placeholder="Ex: Ferramentas Elétricas">
        </div>
        <div class="form-group">
          <label>Descrição</label>
          <textarea id="categoria-descricao" rows="2" placeholder="Descrição da categoria"></textarea>
        </div>
        <div class="form-group">
          <label>Ícone (Emoji)</label>
          <input type="text" id="categoria-icone" placeholder="Ex: ⚡">
        </div>
        <div class="form-group">
          <label>Slug (URL Amigável)</label>
          <input type="text" id="categoria-slug" placeholder="Ex: ferramentas-eletricas">
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="fecharModalCategoria()">Cancelar</button>
          <button type="submit" class="btn btn-accent">💾 Salvar Categoria</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ============================================================
    MODAL CUPOM
    ============================================================ -->
  <div class="modal-overlay" id="modal-cupom">
    <div class="modal">
      <div class="modal-header">
        <h3>Novo Cupom</h3>
        <button class="modal-close" onclick="fecharModalCupom()">✕</button>
      </div>
      <form id="form-cupom" onsubmit="salvarCupom(); return false;">
        <div class="form-group">
          <label>Código do Cupom *</label>
          <input type="text" id="cupom-codigo" required placeholder="Ex: DESCONTO10">
        </div>
        <div class="form-group">
          <label>Descrição</label>
          <input type="text" id="cupom-descricao" placeholder="Ex: 10% de desconto em toda loja">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Tipo de Desconto</label>
            <select id="cupom-tipo">
              <option value="percentual">Percentual (%)</option>
              <option value="fixo">Valor Fixo (R$)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Valor do Desconto *</label>
            <input type="number" step="0.01" id="cupom-valor" required placeholder="0.00">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Data Início</label>
            <input type="date" id="cupom-inicio">
          </div>
          <div class="form-group">
            <label>Data Fim</label>
            <input type="date" id="cupom-fim">
          </div>
        </div>
        <div class="form-group">
          <label>Limite de Usos</label>
          <input type="number" id="cupom-usos" value="1" placeholder="Número máximo de usos">
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="fecharModalCupom()">Cancelar</button>
          <button type="submit" class="btn btn-accent">💾 Salvar Cupom</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container" id="toast-container"></div>


  <script>
    // ============================================================
    // API CONFIG
    // ============================================================
    const API_URL = 'function/api.php';

    // ============================================================
    // FUNÇÕES AUXILIARES
    // ============================================================
    function parseNumero(valor) {
      const num = parseFloat(valor);
      return isNaN(num) ? 0 : num;
    }

    function formatarPreco(valor) {
      const num = parseNumero(valor);
      return `R$ ${num.toFixed(2).replace('.', ',')}`;
    }

    function formatarData(data) {
      if (!data) return '---';
      try {
        return new Date(data).toLocaleDateString('pt-BR');
      } catch {
        return data;
      }
    }

    // ============================================================
    // FUNÇÕES DE API
    // ============================================================
    async function apiRequest(action, method = 'GET', data = null, id = null, params = null) {
      let url = `${API_URL}?action=${action}`;
      if (id) url += `&id=${id}`;

      // Adicionar parâmetros extras (ex: status para pedidos)
      if (params) {
        Object.keys(params).forEach(key => {
          if (params[key]) url += `&${key}=${encodeURIComponent(params[key])}`;
        });
      }

      const options = {
        method: method,
        headers: {
          'Content-Type': 'application/json',
        }
      };

      if (data && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(data);
      }

      try {
        const response = await fetch(url, options);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
      } catch (error) {
        console.error('Erro na requisição:', error);
        return {
          success: false,
          error: error.message
        };
      }
    }

    // ============================================================
    // DASHBOARD
    // ============================================================
    async function loadDashboard() {
      try {
        const result = await apiRequest('dashboard');

        if (result.success && result.data) {
          const data = result.data;

          // Atualizar cards com tratamento de números
          document.getElementById('stat-produtos').textContent = parseNumero(data.total_produtos);
          document.getElementById('stat-pedidos').textContent = parseNumero(data.pedidos_hoje);
          document.getElementById('stat-faturamento').textContent = formatarPreco(data.faturamento_mensal);
          document.getElementById('stat-clientes').textContent = parseNumero(data.clientes_ativos);
          document.getElementById('stat-falta').textContent = parseNumero(data.produtos_falta);

          // Atualizar badges
          document.getElementById('badge-produtos').textContent = parseNumero(data.total_produtos);
          document.getElementById('badge-pedidos').textContent = parseNumero(data.pedidos_hoje);

          // Atualizar últimos pedidos
          const tbody = document.getElementById('ultimos-pedidos');
          if (data.ultimos_pedidos && data.ultimos_pedidos.length > 0) {
            tbody.innerHTML = '';
            data.ultimos_pedidos.forEach(pedido => {
              const statusMap = {
                'pendente': 'pending',
                'pago': 'paid',
                'processando': 'shipped',
                'enviado': 'shipped',
                'entregue': 'delivered',
                'cancelado': 'cancelled'
              };
              const statusClass = statusMap[pedido.status_pedido] || 'pending';
              const statusLabel = {
                'pendente': 'Pendente',
                'pago': 'Pago',
                'processando': 'Processando',
                'enviado': 'Enviado',
                'entregue': 'Entregue',
                'cancelado': 'Cancelado'
              } [pedido.status_pedido] || pedido.status_pedido;

              tbody.innerHTML += `
                            <tr>
                                <td><strong>#${String(pedido.id_pedido || 0).padStart(3, '0')}</strong></td>
                                <td>${pedido.cliente || 'Cliente'}</td>
                                <td>${formatarPreco(pedido.total)}</td>
                                <td>${formatarData(pedido.data_pedido)}</td>
                                <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            </tr>
                        `;
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: var(--gray-500);">Nenhum pedido recente</td></tr>`;
          }

          // Atualizar produtos mais vendidos
          const tbodyVendidos = document.getElementById('mais-vendidos');
          if (data.mais_vendidos && data.mais_vendidos.length > 0) {
            tbodyVendidos.innerHTML = '';
            data.mais_vendidos.forEach(produto => {
              tbodyVendidos.innerHTML += `
                            <tr>
                                <td>${produto.nome || 'Produto'}</td>
                                <td>${produto.categoria || 'Sem categoria'}</td>
                                <td>${parseNumero(produto.vendas)}</td>
                                <td>${formatarPreco(produto.receita)}</td>
                            </tr>
                        `;
            });
          } else {
            tbodyVendidos.innerHTML = `<tr><td colspan="4" style="text-align: center; color: var(--gray-500);">Nenhum produto vendido</td></tr>`;
          }
        } else {
          // Dados simulados para demonstração
          document.getElementById('stat-produtos').textContent = '48';
          document.getElementById('stat-pedidos').textContent = '18';
          document.getElementById('stat-faturamento').textContent = 'R$ 4.250,00';
          document.getElementById('stat-clientes').textContent = '156';
          document.getElementById('stat-falta').textContent = '3';
          document.getElementById('badge-produtos').textContent = '48';
          document.getElementById('badge-pedidos').textContent = '18';
        }
      } catch (error) {
        console.error('Erro ao carregar dashboard:', error);
        // Dados de fallback
        document.getElementById('stat-produtos').textContent = '48';
        document.getElementById('stat-pedidos').textContent = '18';
        document.getElementById('stat-faturamento').textContent = 'R$ 4.250,00';
        document.getElementById('stat-clientes').textContent = '156';
        document.getElementById('stat-falta').textContent = '3';
      }
    }

    // ============================================================
    // PRODUTOS
    // ============================================================
    async function loadProdutos() {
      document.getElementById('produtos-loading').style.display = 'flex';
      document.getElementById('produtos-content').style.display = 'none';

      try {
        const result = await apiRequest('produtos');

        document.getElementById('produtos-loading').style.display = 'none';
        document.getElementById('produtos-content').style.display = 'block';

        if (result.success && result.data) {
          const tbody = document.getElementById('tabela-produtos');
          tbody.innerHTML = '';

          if (result.data.length === 0) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                <div class="empty-state">
                                    <span class="icon">📦</span>
                                    <h4>Nenhum produto cadastrado</h4>
                                    <p>Clique em "Novo Produto" para começar</p>
                                </div>
                            </td>
                        </tr>
                    `;
            return;
          }

          result.data.forEach(produto => {
            // Converter valores com segurança
            const precoVenda = parseNumero(produto.preco_venda);
            const estoqueAtual = parseInt(produto.estoque_atual) || 0;

            const statusClass = produto.status === 'ativo' ? 'active' : 'inactive';
            const statusLabel = produto.status === 'ativo' ? 'Ativo' :
              produto.status === 'inativo' ? 'Inativo' :
              produto.status === 'rascunho' ? 'Rascunho' : 'Esgotado';

            tbody.innerHTML += `
                        <tr>
                            <td>${produto.id_produto || 0}</td>
                            <td><div class="product-thumb">${produto.imagem_principal ? '🖼️' : '📦'}</div></td>
                            <td><strong>${produto.nome || 'Sem nome'}</strong></td>
                            <td>${produto.sku || '---'}</td>
                            <td>${formatarPreco(precoVenda)}</td>
                            <td>${estoqueAtual}</td>
                            <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="editarProduto(${produto.id_produto})">✏️</button>
                                <button class="btn btn-danger btn-sm" onclick="excluirProduto(${produto.id_produto})">🗑️</button>
                            </td>
                        </tr>
                    `;
          });
        } else {
          document.getElementById('tabela-produtos').innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--gray-500);">Erro ao carregar produtos: ${result.error || 'Erro desconhecido'}</td>
                    </tr>
                `;
        }
      } catch (error) {
        console.error('Erro ao carregar produtos:', error);
        document.getElementById('produtos-loading').style.display = 'none';
        document.getElementById('produtos-content').style.display = 'block';
        document.getElementById('tabela-produtos').innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--danger);">Erro ao carregar produtos: ${error.message}</td>
                </tr>
            `;
      }
    }

    async function criarProduto(data) {
      const result = await apiRequest('produto', 'POST', data);
      if (result.success) {
        showToast('✅ Produto criado com sucesso!', 'success');
        loadProdutos();
        fecharModal();
      } else {
        showToast('❌ Erro ao criar produto: ' + (result.error || 'Erro desconhecido'), 'error');
      }
      return result;
    }

    async function editarProduto(id) {
      try {
        const result = await apiRequest('produto', 'GET', null, id);
        if (result.success && result.data) {
          const produto = result.data;
          document.getElementById('modal-titulo').textContent = 'Editar Produto';
          document.getElementById('produto-id').value = produto.id_produto || '';
          document.getElementById('produto-nome').value = produto.nome || '';
          document.getElementById('produto-descricao').value = produto.descricao || '';
          document.getElementById('produto-sku').value = produto.sku || '';
          document.getElementById('produto-preco').value = parseNumero(produto.preco_venda);
          document.getElementById('produto-estoque').value = parseInt(produto.estoque_atual) || 0;
          document.getElementById('produto-status').value = produto.status || 'ativo';
          document.getElementById('produto-destaque').checked = produto.destaque == 1;
          document.getElementById('produto-novo').checked = produto.novo == 1;

          document.getElementById('modal-produto').classList.add('show');
        } else {
          showToast('❌ Erro ao carregar produto: ' + (result.error || 'Produto não encontrado'), 'error');
        }
      } catch (error) {
        showToast('❌ Erro ao carregar produto: ' + error.message, 'error');
      }
    }

    async function excluirProduto(id) {
      if (confirm('Tem certeza que deseja excluir este produto?')) {
        const result = await apiRequest('produto', 'DELETE', null, id);
        if (result.success) {
          showToast('🗑️ Produto removido com sucesso!', 'success');
          loadProdutos();
        } else {
          showToast('❌ Erro ao excluir produto: ' + (result.error || 'Erro desconhecido'), 'error');
        }
      }
    }

    async function salvarProduto() {
      const id = document.getElementById('produto-id').value;
      const data = {
        nome: document.getElementById('produto-nome').value.trim(),
        descricao: document.getElementById('produto-descricao').value.trim(),
        sku: document.getElementById('produto-sku').value.trim().toUpperCase(),
        preco_venda: parseNumero(document.getElementById('produto-preco').value),
        estoque_atual: parseInt(document.getElementById('produto-estoque').value) || 0,
        status: document.getElementById('produto-status').value,
        destaque: document.getElementById('produto-destaque').checked ? 1 : 0,
        novo: document.getElementById('produto-novo').checked ? 1 : 0
      };

      // Validação básica
      if (!data.nome) {
        showToast('⚠️ O nome do produto é obrigatório', 'warning');
        return;
      }
      if (data.preco_venda <= 0) {
        showToast('⚠️ O preço de venda deve ser maior que zero', 'warning');
        return;
      }

      let result;
      if (id) {
        result = await apiRequest('produto', 'PUT', data, id);
      } else {
        result = await apiRequest('produto', 'POST', data);
      }

      if (result.success) {
        showToast('✅ Produto salvo com sucesso!', 'success');
        loadProdutos();
        fecharModal();
      } else {
        showToast('❌ Erro ao salvar produto: ' + (result.error || 'Erro desconhecido'), 'error');
      }
    }

    // ============================================================
    // PEDIDOS
    // ============================================================
    async function loadPedidos() {
      const status = document.getElementById('filtro-status-pedido').value;

      document.getElementById('pedidos-loading').style.display = 'flex';
      document.getElementById('pedidos-content').style.display = 'none';

      try {
        const result = await apiRequest('pedidos', 'GET', null, null, {
          status: status || undefined
        });

        document.getElementById('pedidos-loading').style.display = 'none';
        document.getElementById('pedidos-content').style.display = 'block';

        if (result.success && result.data) {
          const tbody = document.getElementById('tabela-pedidos');
          tbody.innerHTML = '';

          if (result.data.length === 0) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                <div class="empty-state">
                                    <span class="icon">📋</span>
                                    <h4>Nenhum pedido encontrado</h4>
                                    <p>Os pedidos aparecerão aqui quando forem realizados</p>
                                </div>
                            </td>
                        </tr>
                    `;
            return;
          }

          result.data.forEach(pedido => {
            const statusMap = {
              'pendente': 'pending',
              'pago': 'paid',
              'processando': 'shipped',
              'enviado': 'shipped',
              'entregue': 'delivered',
              'cancelado': 'cancelled'
            };
            const statusClass = statusMap[pedido.status_pedido] || 'pending';
            const statusLabel = {
              'pendente': 'Pendente',
              'pago': 'Pago',
              'processando': 'Processando',
              'enviado': 'Enviado',
              'entregue': 'Entregue',
              'cancelado': 'Cancelado'
            } [pedido.status_pedido] || pedido.status_pedido;

            const itensCount = pedido.itens ? pedido.itens.length : 0;

            tbody.innerHTML += `
                        <tr>
                            <td><strong>#${String(pedido.id_pedido || 0).padStart(3, '0')}</strong></td>
                            <td>${pedido.cliente || 'Cliente'}</td>
                            <td>${itensCount} itens</td>
                            <td>${formatarPreco(pedido.total)}</td>
                            <td>${formatarData(pedido.data_pedido)}</td>
                            <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            <td>
                                <select onchange="atualizarStatusPedido(${pedido.id_pedido}, this.value)" class="btn btn-outline btn-sm">
                                    <option value="pendente" ${pedido.status_pedido === 'pendente' ? 'selected' : ''}>Pendente</option>
                                    <option value="pago" ${pedido.status_pedido === 'pago' ? 'selected' : ''}>Pago</option>
                                    <option value="processando" ${pedido.status_pedido === 'processando' ? 'selected' : ''}>Processando</option>
                                    <option value="enviado" ${pedido.status_pedido === 'enviado' ? 'selected' : ''}>Enviado</option>
                                    <option value="entregue" ${pedido.status_pedido === 'entregue' ? 'selected' : ''}>Entregue</option>
                                    <option value="cancelado" ${pedido.status_pedido === 'cancelado' ? 'selected' : ''}>Cancelado</option>
                                </select>
                            </td>
                        </tr>
                    `;
          });
        } else {
          document.getElementById('tabela-pedidos').innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray-500);">Erro ao carregar pedidos</td>
                    </tr>
                `;
        }
      } catch (error) {
        console.error('Erro ao carregar pedidos:', error);
        document.getElementById('pedidos-loading').style.display = 'none';
        document.getElementById('pedidos-content').style.display = 'block';
        document.getElementById('tabela-pedidos').innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--danger);">Erro ao carregar pedidos: ${error.message}</td>
                </tr>
            `;
      }
    }

    async function atualizarStatusPedido(id, status) {
      try {
        const result = await apiRequest('pedido', 'PUT', {
          status
        }, id);
        if (result.success) {
          showToast('✅ Status do pedido atualizado!', 'success');
          loadPedidos();
        } else {
          showToast('❌ Erro ao atualizar status: ' + (result.error || 'Erro desconhecido'), 'error');
        }
      } catch (error) {
        showToast('❌ Erro ao atualizar status: ' + error.message, 'error');
      }
    }

    // ============================================================
    // CLIENTES
    // ============================================================
    async function loadClientes() {
      document.getElementById('clientes-loading').style.display = 'flex';
      document.getElementById('clientes-content').style.display = 'none';

      try {
        const result = await apiRequest('clientes');

        document.getElementById('clientes-loading').style.display = 'none';
        document.getElementById('clientes-content').style.display = 'block';

        if (result.success && result.data) {
          const tbody = document.getElementById('tabela-clientes');
          tbody.innerHTML = '';

          if (result.data.length === 0) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                <div class="empty-state">
                                    <span class="icon">👤</span>
                                    <h4>Nenhum cliente cadastrado</h4>
                                    <p>Os clientes aparecerão aqui quando se cadastrarem</p>
                                </div>
                            </td>
                        </tr>
                    `;
            return;
          }

          result.data.forEach(cliente => {
            const statusClass = cliente.status === 'ativo' ? 'active' : 'inactive';
            const statusLabel = cliente.status === 'ativo' ? 'Ativo' : 'Inativo';

            tbody.innerHTML += `
                        <tr>
                            <td>${cliente.id_usuario || 0}</td>
                            <td><strong>${cliente.nome_completo || 'Sem nome'}</strong></td>
                            <td>${cliente.email || '---'}</td>
                            <td>${cliente.cpf || '---'}</td>
                            <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            <td>${formatarData(cliente.data_cadastro)}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="verCliente(${cliente.id_usuario})">👁️</button>
                            </td>
                        </tr>
                    `;
          });
        } else {
          document.getElementById('tabela-clientes').innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray-500);">Erro ao carregar clientes</td>
                    </tr>
                `;
        }
      } catch (error) {
        console.error('Erro ao carregar clientes:', error);
        document.getElementById('clientes-loading').style.display = 'none';
        document.getElementById('clientes-content').style.display = 'block';
        document.getElementById('tabela-clientes').innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--danger);">Erro ao carregar clientes: ${error.message}</td>
                </tr>
            `;
      }
    }

    async function verCliente(id) {
      try {
        const result = await apiRequest('cliente', 'GET', null, id);
        if (result.success && result.data) {
          const cliente = result.data;
          let enderecosStr = 'Nenhum endereço cadastrado';
          if (cliente.enderecos && cliente.enderecos.length > 0) {
            enderecosStr = cliente.enderecos.map(e =>
              `${e.logradouro || ''}, ${e.numero || ''} - ${e.bairro || ''}, ${e.cidade || ''}/${e.estado || ''}`
            ).join('\n');
          }

          alert(`👤 Dados do Cliente\n\n` +
            `Nome: ${cliente.nome_completo || '---'}\n` +
            `Email: ${cliente.email || '---'}\n` +
            `CPF: ${cliente.cpf || 'Não informado'}\n` +
            `Telefone: ${cliente.telefone || 'Não informado'}\n` +
            `Status: ${cliente.status || '---'}\n` +
            `Cadastro: ${formatarData(cliente.data_cadastro)}\n\n` +
            `📌 Endereços:\n${enderecosStr}`);
        } else {
          showToast('❌ Erro ao carregar cliente: ' + (result.error || 'Cliente não encontrado'), 'error');
        }
      } catch (error) {
        showToast('❌ Erro ao carregar cliente: ' + error.message, 'error');
      }
    }

    // ============================================================
    // CATEGORIAS
    // ============================================================
    async function loadCategorias() {
      document.getElementById('categorias-loading').style.display = 'flex';
      document.getElementById('categorias-content').style.display = 'none';

      try {
        const result = await apiRequest('categorias');

        document.getElementById('categorias-loading').style.display = 'none';
        document.getElementById('categorias-content').style.display = 'block';

        if (result.success && result.data) {
          const tbody = document.getElementById('tabela-categorias');
          tbody.innerHTML = '';

          if (result.data.length === 0) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                <div class="empty-state">
                                    <span class="icon">📂</span>
                                    <h4>Nenhuma categoria cadastrada</h4>
                                    <p>Clique em "Nova Categoria" para começar</p>
                                </div>
                            </td>
                        </tr>
                    `;
            return;
          }

          result.data.forEach(categoria => {
            const statusClass = categoria.status === 'ativo' ? 'active' : 'inactive';
            const statusLabel = categoria.status === 'ativo' ? 'Ativo' : 'Inativo';

            tbody.innerHTML += `
                        <tr>
                            <td>${categoria.id_categoria || 0}</td>
                            <td style="font-size: 1.5rem;">${categoria.icone || '📂'}</td>
                            <td><strong>${categoria.nome || 'Sem nome'}</strong></td>
                            <td>${categoria.slug || '---'}</td>
                            <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="excluirCategoria(${categoria.id_categoria})">🗑️</button>
                            </td>
                        </tr>
                    `;
          });
        } else {
          document.getElementById('tabela-categorias').innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--gray-500);">Erro ao carregar categorias</td>
                    </tr>
                `;
        }
      } catch (error) {
        console.error('Erro ao carregar categorias:', error);
        document.getElementById('categorias-loading').style.display = 'none';
        document.getElementById('categorias-content').style.display = 'block';
        document.getElementById('tabela-categorias').innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--danger);">Erro ao carregar categorias: ${error.message}</td>
                </tr>
            `;
      }
    }

    function abrirModalCategoria() {
      document.getElementById('modal-categoria').classList.add('show');
      document.getElementById('categoria-nome').value = '';
      document.getElementById('categoria-descricao').value = '';
      document.getElementById('categoria-icone').value = '';
      document.getElementById('categoria-slug').value = '';
    }

    function fecharModalCategoria() {
      document.getElementById('modal-categoria').classList.remove('show');
    }

    async function salvarCategoria() {
      const data = {
        nome: document.getElementById('categoria-nome').value.trim(),
        descricao: document.getElementById('categoria-descricao').value.trim(),
        icone: document.getElementById('categoria-icone').value.trim(),
        slug: document.getElementById('categoria-slug').value.trim() ||
          document.getElementById('categoria-nome').value.trim().toLowerCase().replace(/ /g, '-')
      };

      if (!data.nome) {
        showToast('⚠️ O nome da categoria é obrigatório', 'warning');
        return;
      }

      const result = await apiRequest('categoria', 'POST', data);
      if (result.success) {
        showToast('✅ Categoria criada com sucesso!', 'success');
        loadCategorias();
        fecharModalCategoria();
      } else {
        showToast('❌ Erro ao criar categoria: ' + (result.error || 'Erro desconhecido'), 'error');
      }
    }

    async function excluirCategoria(id) {
      if (confirm('Tem certeza que deseja excluir esta categoria?')) {
        try {
          const result = await apiRequest('categoria', 'DELETE', null, id);
          if (result.success) {
            showToast('✅ Categoria removida com sucesso!', 'success');
            loadCategorias();
          } else {
            showToast('❌ Erro ao excluir categoria: ' + (result.error || 'Erro desconhecido'), 'error');
          }
        } catch (error) {
          showToast('❌ Erro ao excluir categoria: ' + error.message, 'error');
        }
      }
    }

    // ============================================================
    // CUPONS
    // ============================================================
    async function loadCupons() {
      document.getElementById('cupons-loading').style.display = 'flex';
      document.getElementById('cupons-content').style.display = 'none';

      try {
        const result = await apiRequest('cupons');

        document.getElementById('cupons-loading').style.display = 'none';
        document.getElementById('cupons-content').style.display = 'block';

        if (result.success && result.data) {
          const tbody = document.getElementById('tabela-cupons');
          tbody.innerHTML = '';

          if (result.data.length === 0) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                <div class="empty-state">
                                    <span class="icon">🎫</span>
                                    <h4>Nenhum cupom cadastrado</h4>
                                    <p>Clique em "Novo Cupom" para começar</p>
                                </div>
                            </td>
                        </tr>
                    `;
            return;
          }

          result.data.forEach(cupom => {
            const statusClass = cupom.status === 'ativo' ? 'active' : 'inactive';
            const statusLabel = cupom.status === 'ativo' ? 'Ativo' : 'Inativo';
            const descontoStr = cupom.tipo_desconto === 'percentual' ?
              `${parseNumero(cupom.valor_desconto)}%` :
              formatarPreco(cupom.valor_desconto);
            const validade = `${formatarData(cupom.data_inicio)} - ${formatarData(cupom.data_fim)}`;

            tbody.innerHTML += `
                        <tr>
                            <td>${cupom.id_cupom || 0}</td>
                            <td><strong>${cupom.codigo || '---'}</strong></td>
                            <td>${descontoStr}</td>
                            <td>${validade}</td>
                            <td>${parseNumero(cupom.uso_atual)}/${parseNumero(cupom.uso_total)}</td>
                            <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="excluirCupom(${cupom.id_cupom})">🗑️</button>
                            </td>
                        </tr>
                    `;
          });
        } else {
          document.getElementById('tabela-cupons').innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray-500);">Erro ao carregar cupons</td>
                    </tr>
                `;
        }
      } catch (error) {
        console.error('Erro ao carregar cupons:', error);
        document.getElementById('cupons-loading').style.display = 'none';
        document.getElementById('cupons-content').style.display = 'block';
        document.getElementById('tabela-cupons').innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--danger);">Erro ao carregar cupons: ${error.message}</td>
                </tr>
            `;
      }
    }

    function abrirModalCupom() {
      document.getElementById('modal-cupom').classList.add('show');
      document.getElementById('cupom-codigo').value = '';
      document.getElementById('cupom-descricao').value = '';
      document.getElementById('cupom-tipo').value = 'percentual';
      document.getElementById('cupom-valor').value = '';
      document.getElementById('cupom-inicio').value = '';
      document.getElementById('cupom-fim').value = '';
      document.getElementById('cupom-usos').value = '1';
    }

    function fecharModalCupom() {
      document.getElementById('modal-cupom').classList.remove('show');
    }

    async function salvarCupom() {
      const data = {
        codigo: document.getElementById('cupom-codigo').value.trim().toUpperCase(),
        descricao: document.getElementById('cupom-descricao').value.trim(),
        tipo_desconto: document.getElementById('cupom-tipo').value,
        valor_desconto: parseNumero(document.getElementById('cupom-valor').value),
        data_inicio: document.getElementById('cupom-inicio').value || new Date().toISOString().split('T')[0],
        data_fim: document.getElementById('cupom-fim').value || new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
        uso_total: parseInt(document.getElementById('cupom-usos').value) || 1,
        status: 'ativo'
      };

      if (!data.codigo) {
        showToast('⚠️ O código do cupom é obrigatório', 'warning');
        return;
      }
      if (data.valor_desconto <= 0) {
        showToast('⚠️ O valor do desconto deve ser maior que zero', 'warning');
        return;
      }

      const result = await apiRequest('cupom', 'POST', data);
      if (result.success) {
        showToast('✅ Cupom criado com sucesso!', 'success');
        loadCupons();
        fecharModalCupom();
      } else {
        showToast('❌ Erro ao criar cupom: ' + (result.error || 'Erro desconhecido'), 'error');
      }
    }

    async function excluirCupom(id) {
      if (confirm('Tem certeza que deseja excluir este cupom?')) {
        try {
          const result = await apiRequest('cupom', 'DELETE', null, id);
          if (result.success) {
            showToast('✅ Cupom removido com sucesso!', 'success');
            loadCupons();
          } else {
            showToast('❌ Erro ao excluir cupom: ' + (result.error || 'Erro desconhecido'), 'error');
          }
        } catch (error) {
          showToast('❌ Erro ao excluir cupom: ' + error.message, 'error');
        }
      }
    }

    // ============================================================
    // CONFIGURAÇÕES
    // ============================================================
    function salvarConfiguracoes() {
      const config = {
        nome_loja: document.getElementById('config-nome-loja').value,
        email_contato: document.getElementById('config-email-contato').value,
        telefone: document.getElementById('config-telefone').value,
        moeda: document.getElementById('config-moeda').value,
        frete_padrao: parseNumero(document.getElementById('config-frete').value),
        status_loja: document.getElementById('config-status-loja').value,
        estoque_negativo: document.getElementById('config-estoque-negativo').checked
      };

      // Salvar no localStorage para persistência local
      localStorage.setItem('construmix_config', JSON.stringify(config));

      showToast('✅ Configurações salvas com sucesso!', 'success');
    }

    function carregarConfiguracoes() {
      const saved = localStorage.getItem('construmix_config');
      if (saved) {
        try {
          const config = JSON.parse(saved);
          document.getElementById('config-nome-loja').value = config.nome_loja || 'Construmix';
          document.getElementById('config-email-contato').value = config.email_contato || 'contato@construmix.com';
          document.getElementById('config-telefone').value = config.telefone || '(11) 99999-9999';
          document.getElementById('config-moeda').value = config.moeda || 'BRL';
          document.getElementById('config-frete').value = config.frete_padrao || 15.00;
          document.getElementById('config-status-loja').value = config.status_loja || 'ativa';
          document.getElementById('config-estoque-negativo').checked = config.estoque_negativo !== undefined ? config.estoque_negativo : true;
        } catch (e) {
          console.error('Erro ao carregar configurações:', e);
        }
      }
    }

    // ============================================================
    // TOAST NOTIFICATION
    // ============================================================
    function showToast(message, type = 'info') {
      const container = document.getElementById('toast-container');

      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.textContent = message;
      container.appendChild(toast);

      // Forçar reflow para animação
      void toast.offsetWidth;

      setTimeout(() => toast.classList.add('show'), 10);

      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
      }, 3000);
    }

    // ============================================================
    // MODAL FUNCTIONS
    // ============================================================
    function abrirModalProduto() {
      document.getElementById('modal-titulo').textContent = 'Novo Produto';
      document.getElementById('produto-id').value = '';
      document.getElementById('produto-nome').value = '';
      document.getElementById('produto-descricao').value = '';
      document.getElementById('produto-sku').value = '';
      document.getElementById('produto-preco').value = '';
      document.getElementById('produto-estoque').value = '0';
      document.getElementById('produto-status').value = 'ativo';
      document.getElementById('produto-destaque').checked = false;
      document.getElementById('produto-novo').checked = false;
      document.getElementById('modal-produto').classList.add('show');
    }

    function fecharModal() {
      document.getElementById('modal-produto').classList.remove('show');
    }

    // ============================================================
    // NAVEGAÇÃO
    // ============================================================
    function showModule(module) {
      // Esconder todos os módulos
      document.querySelectorAll('.module').forEach(m => m.classList.remove('active'));

      // Mostrar o módulo selecionado
      const target = document.getElementById(`mod-${module}`);
      if (target) target.classList.add('active');

      // Atualizar menu
      document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
      document.querySelector(`.sidebar-menu a[data-module="${module}"]`)?.classList.add('active');

      // Atualizar título
      const titles = {
        'dashboard': 'Dashboard',
        'produtos': 'Produtos',
        'pedidos': 'Pedidos',
        'clientes': 'Clientes',
        'categorias': 'Categorias',
        'cupons': 'Cupons',
        'configuracoes': 'Configurações'
      };
      const subtitles = {
        'dashboard': 'Visão geral da loja',
        'produtos': 'Gerenciar produtos da loja',
        'pedidos': 'Gerenciar pedidos dos clientes',
        'clientes': 'Gerenciar clientes cadastrados',
        'categorias': 'Gerenciar categorias de produtos',
        'cupons': 'Gerenciar cupons de desconto',
        'configuracoes': 'Configurações do sistema'
      };
      document.querySelector('.page-title').innerHTML = `${titles[module] || module} <small>${subtitles[module] || ''}</small>`;

      // Carregar dados conforme módulo
      switch (module) {
        case 'dashboard':
          loadDashboard();
          break;
        case 'produtos':
          loadProdutos();
          break;
        case 'pedidos':
          loadPedidos();
          break;
        case 'clientes':
          loadClientes();
          break;
        case 'categorias':
          loadCategorias();
          break;
        case 'cupons':
          loadCupons();
          break;
      }
    }

    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
    }

    function logout() {
      if (confirm('Deseja realmente sair?')) {
        window.location.href = 'login.php';
      }
    }

    // ============================================================
    // INICIALIZAÇÃO
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
      // Menu navigation
      document.querySelectorAll('.sidebar-menu a[data-module]').forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          const module = link.dataset.module;
          showModule(module);

          // Fechar sidebar em mobile
          if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('open');
          }
        });
      });

      // Fechar modal com ESC
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          fecharModal();
          fecharModalCategoria();
          fecharModalCupom();
        }
      });

      // Clicar fora do modal para fechar
      document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
          if (e.target === e.currentTarget) {
            modal.classList.remove('show');
          }
        });
      });

      // Carregar configurações
      carregarConfiguracoes();

      // Carregar dashboard inicial
      loadDashboard();

      // Mostrar mensagem de boas-vindas
      setTimeout(() => {
        showToast('👋 Bem-vindo ao Painel Construmix!', 'success');
      }, 500);
    });

    // ============================================================
    // EXPORTAR FUNÇÕES PARA O GLOBAL
    // ============================================================
    window.showModule = showModule;
    window.toggleSidebar = toggleSidebar;
    window.logout = logout;
    window.abrirModalProduto = abrirModalProduto;
    window.fecharModal = fecharModal;
    window.salvarProduto = salvarProduto;
    window.editarProduto = editarProduto;
    window.excluirProduto = excluirProduto;
    window.atualizarStatusPedido = atualizarStatusPedido;
    window.showToast = showToast;
    window.loadPedidos = loadPedidos;
    window.loadClientes = loadClientes;
    window.verCliente = verCliente;
    window.abrirModalCategoria = abrirModalCategoria;
    window.fecharModalCategoria = fecharModalCategoria;
    window.salvarCategoria = salvarCategoria;
    window.excluirCategoria = excluirCategoria;
    window.abrirModalCupom = abrirModalCupom;
    window.fecharModalCupom = fecharModalCupom;
    window.salvarCupom = salvarCupom;
    window.excluirCupom = excluirCupom;
    window.salvarConfiguracoes = salvarConfiguracoes;
  </script>


</body>

</html>