<?php
session_start();
require_once '../function/conexao.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'];
$quantity = $data['quantity'] ?? 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Verifica se o produto já está no carrinho
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $productId) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = [
        'product_id' => $productId,
        'quantity' => $quantity
    ];
}

// Calcula o total de itens
$totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));

echo json_encode([
    'success' => true,
    'count' => $totalItems
]);
?>