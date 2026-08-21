<?php
// session_check.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && 
           isset($_SESSION['logado']) && 
           $_SESSION['logado'] === true;
}

function checkAuth() {
    if (!isLoggedIn()) {
        header('Location: ../login/index.php');
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