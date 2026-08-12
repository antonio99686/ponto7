<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Cadastro de Produto</title>
</head>

<body>

<?php
require_once "function/conexao.php";
$conexao = conn();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'])) {
    // Inicializa nomes das imagens como vazios
    $img_principal = '';
    $img_hover = '';
    
    // Coletando dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $estoque = $_POST['estoque'] ?? 0;
    $preco_original = $_POST['preco_original'] ?? 0;
    $desconto = $_POST['desconto'] ?? 0;
    $avaliacao = $_POST['avaliacao'] ?? 0;
    
    // Calculando preço promocional
    $preco_promocional = $preco_original - ($preco_original * ($desconto / 100));
    
    // Data atual para o cadastro
    $data_cadastro = date('Y-m-d H:i:s');

    // Processamento da imagem principal ANTES da inserção no banco
    if (isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagem_principal']['tmp_name'];
        $fileName = $_FILES['imagem_principal']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Gera um nome único para a imagem
        $newFileName = md5(time() . $fileName) . '_principal.' . $fileExtension;
        $uploadFileDir = '../imgProdutos/';
        $dest_path = $uploadFileDir . $newFileName;

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $img_principal = $newFileName;
            }
        }
    }

    // Processamento da imagem hover ANTES da inserção no banco
    if (isset($_FILES['imagem_hover']) && $_FILES['imagem_hover']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagem_hover']['tmp_name'];
        $fileName = $_FILES['imagem_hover']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Gera um nome único para a imagem
        $newFileName = md5(time() . $fileName) . '_hover.' . $fileExtension;
        $uploadFileDir = '../imgProdutos/';
        $dest_path = $uploadFileDir . $newFileName;

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $img_hover = $newFileName;
            }
        }
    }

    // Se nenhuma imagem foi enviada, usa imagens padrão
    if (empty($img_principal)) {
        $img_principal = 'padrao_principal.jpg';
    }
    if (empty($img_hover)) {
        $img_hover = 'padrao_hover.jpg';
    }

    // Insere dados do produto no banco de dados
    $sql = "INSERT INTO produtos (nome, descricao, imagem_principal, imagem_hover, categoria, estoque, data_cadastro, preco_original, desconto, preco_promocional, avaliacao) 
            VALUES ('$nome', '$descricao', '$img_principal', '$img_hover', '$categoria', $estoque, '$data_cadastro', $preco_original, $desconto, $preco_promocional, $avaliacao)";
    
    if (executarSQL($conexao, $sql)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Produto cadastrado com sucesso!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '../produtos.php';
            });
        </script>";
    } else {
        // Se houve erro, remove as imagens que foram enviadas
        if ($img_principal != 'padrao_principal.jpg' && file_exists('../imgProdutos/' . $img_principal)) {
            unlink('../imgProdutos/' . $img_principal);
        }
        if ($img_hover != 'padrao_hover.jpg' && file_exists('../imgProdutos/' . $img_hover)) {
            unlink('../imgProdutos/' . $img_hover);
        }
        
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Falha ao cadastrar Produto.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '../admin/index.php';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Dados do formulário incompletos.',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = '../admin/ofertas/cad/cadOferta.php';
        });
    </script>";
}
?>

</body>
</html>