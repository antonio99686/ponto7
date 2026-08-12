<?php
session_start();
require_once '../function/conexao.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'];
$action = $data['action'];

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($action === 'add') {
    if (!in_array($productId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $productId;
    }
} else {
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$productId]);
}

echo json_encode([
    'success' => true,
    'count' => count($_SESSION['wishlist'])
]);
?>