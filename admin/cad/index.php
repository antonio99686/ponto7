<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <h1>Cadastro de Produto</h1>

    <form action="../../crud/cadastrar.php" method="POST" enctype="multipart/form-data">
        <label for="produtos">Nome do Produto:</label><br>
        <input type="text" id="produtos" name="produto" required><br><br>

        <label for="valor">Valor:</label><br>
        <input type="number" step="0.01" id="valor" name="valor" required><br><br>

        <label for="file">Imagem do Produto:</label><br>
        <input type="file" id="file" name="file" accept="image/*"><br><br>

        <input type="submit" value="Cadastrar Produto">
    </form>

</body>
</html>
