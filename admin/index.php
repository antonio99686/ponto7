<?php
// admin/index.php
session_start();
require_once '../config/database.php';

// Verificar se está logado como admin
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: ../login/index.php');
    exit();
}

// Verificar se é admin
if ($_SESSION['usuario_tipo'] !== 'admin' && $_SESSION['usuario_tipo'] !== 'vendedor') {
    header('Location: ../index.php');
    exit();
}

$admin_nome = $_SESSION['usuario_nome'];

// Buscar estatísticas
// Total de produtos
$stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE status = 'ativo'");
$total_produtos = $stmt->fetch()['total'] ?? 0;

// Total de pedidos hoje
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pedidos WHERE DATE(data_pedido) = CURDATE()");
$pedidos_hoje = $stmt->fetch()['total'] ?? 0;

// Faturamento mensal
$stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) as total FROM pedidos 
                     WHERE MONTH(data_pedido) = MONTH(CURDATE()) 
                     AND YEAR(data_pedido) = YEAR(CURDATE())
                     AND status_pedido IN ('pago', 'processando', 'enviado', 'entregue')");
$faturamento = $stmt->fetch()['total'] ?? 0;

// Total de clientes
$stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'cliente'");
$total_clientes = $stmt->fetch()['total'] ?? 0;

// Produtos em falta
$stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos WHERE estoque_atual <= estoque_minimo AND status = 'ativo'");
$produtos_falta = $stmt->fetch()['total'] ?? 0;

// Últimos pedidos
$stmt = $pdo->query("SELECT p.*, u.nome_completo as cliente 
                     FROM pedidos p 
                     LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                     ORDER BY p.data_pedido DESC LIMIT 5");
$ultimos_pedidos = $stmt->fetchAll();

// Produtos mais vendidos
$stmt = $pdo->query("SELECT pr.nome, c.nome as categoria, SUM(ip.quantidade) as vendas, 
                     SUM(ip.subtotal) as receita
                     FROM itens_pedido ip
                     JOIN produtos pr ON ip.id_produto = pr.id_produto
                     LEFT JOIN categorias c ON pr.categoria_id = c.id_categoria
                     JOIN pedidos p ON ip.id_pedido = p.id_pedido
                     WHERE p.status_pedido IN ('pago', 'processando', 'enviado', 'entregue')
                     GROUP BY ip.id_produto
                     ORDER BY vendas DESC LIMIT 3");
$mais_vendidos = $stmt->fetchAll();

// Últimos clientes
$stmt = $pdo->query("SELECT id_usuario, nome_completo, email, data_cadastro 
                     FROM usuarios 
                     WHERE tipo_usuario = 'cliente' 
                     ORDER BY data_cadastro DESC LIMIT 5");
$ultimos_clientes = $stmt->fetchAll();

// Total de vendas do dia
$stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE DATE(data_pedido) = CURDATE()");
$vendas_hoje = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>⚡ Painel Admin - Ferragem Ponto 7</title>
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
            --gray-50: #f7f8