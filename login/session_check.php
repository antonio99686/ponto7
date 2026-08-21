<?php
// session_check.php - Arquivo para verificar sessão em todas as páginas
session_start();

function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && 
           isset($_SESSION['logado']) && 
           $_SESSION['logado'] === true;
}

function isAdmin() {
    return isLoggedIn() && 
           ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'vendedor');
}

function isCliente() {
    return isLoggedIn() && $_SESSION['usuario_tipo'] === 'cliente';
}

function checkAuth($tipo = null) {
    if (!isLoggedIn()) {
        header('Location: login/index.php');
        exit();
    }
    
    if ($tipo === 'admin' && !isAdmin()) {
        header('Location: ../index.php');
        exit();
    }
    
    if ($tipo === 'cliente' && !isCliente()) {
        header('Location: ../admin/index.php');
        exit();
    }
    
    return true;
}

function getUserData() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'tipo' => $_SESSION['usuario_tipo']
        ];
    }
    return null;
}
?>