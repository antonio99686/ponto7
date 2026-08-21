<?php
// create_admin.php - Script para criar usuário admin
    

$nome = 'Administrador';
$email = 'admin@construmix.com';
$senha = 'admin123'; // Senha padrão
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

try {
    // Verificar se o email já existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo "Usuário admin já existe!\n";
        echo "Email: admin@construmix.com\n";
        echo "Senha: admin123\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome_completo, email, senha_hash, tipo_usuario, status) 
VALUES (?, ?, ?, 'admin', 'ativo')");
  //      $stmt->execute([$nome, $email, $senha_hash]);
  //      echo "✅ Usuário admin criado com sucesso!\n";
//echo "Email: admin@construmix.com\n";
   //     echo "Senha: admin123\n";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>