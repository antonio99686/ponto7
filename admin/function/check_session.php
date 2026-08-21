<?php
// api/check_session.php
session_start();

header('Content-Type: application/json');

$response = [
    'logado' => isset($_SESSION['usuario_id']) && 
               isset($_SESSION['logado']) && 
               $_SESSION['logado'] === true,
    'usuario' => null
];

if ($response['logado']) {
    $response['usuario'] = [
        'id' => $_SESSION['usuario_id'],
        'nome' => $_SESSION['usuario_nome'],
        'email' => $_SESSION['usuario_email'],
        'tipo' => $_SESSION['usuario_tipo']
    ];
}

echo json_encode($response);
?>