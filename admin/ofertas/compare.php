<?php
session_start();
require_once '../function/conexao.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'];

if (!isset($_SESSION['compare'])) {
    $_SESSION['compare'] = [];
}

// Limita a comparação a 4 produtos
if (count($_SESSION['compare']) >= 4) {
    echo json_encode([
        'success' => false,
        'message' => 'Você pode comparar no máximo 4 produtos'
    ]);
    exit;
}

if (!in_array($productId, $_SESSION['compare'])) {
    $_SESSION['compare'][] = $productId;
}

echo json_encode([
    'success' => true,
    'count' => count($_SESSION['compare'])
]);
?>