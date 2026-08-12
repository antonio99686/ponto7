<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 40px;
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastro de Produto</h2>
    <form action="../../../crud/cadOfertas.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="imagem_principal" class="form-label">Imagem Principal</label>
            <input type="file" class="form-control" id="imagem_principal" name="imagem_principal" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="imagem_hover" class="form-label">Imagem Hover</label>
            <input type="file" class="form-control" id="imagem_hover" name="imagem_hover" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria">
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque</label>
            <input type="number" class="form-control" id="estoque" name="estoque" min="0" value="0">
        </div>

        <div class="mb-3">
            <label for="preco_original" class="form-label">Preço Original (R$)</label>
            <input type="number" class="form-control" id="preco_original" name="preco_original" step="0.01">
        </div>

        <div class="mb-3">
            <label for="desconto" class="form-label">Desconto (%)</label>
            <input type="number" class="form-control" id="desconto" name="desconto" step="0.01">
        </div>

        <div class="mb-3">
            <label for="preco_promocional" class="form-label">Preço Promocional (R$)</label>
            <input type="number" class="form-control" id="preco_promocional" name="preco_promocional" step="0.01">
        </div>

        <div class="mb-3">
            <label for="avaliacao" class="form-label">Avaliação (0 a 5)</label>
            <input type="number" class="form-control" id="avaliacao" name="avaliacao" step="0.1" min="0" max="5">
        </div>

        <button type="submit" class="btn btn-primary w-100">Cadastrar Produto</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
