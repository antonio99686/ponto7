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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto'], $_POST['valor'])) {
        $produto = $_POST['produto'];
        $valor = $_POST['valor'];
        $img_padrao = 'tapete_croche.jfif'; // Nome da imagem padrão

        // Insere dados do produto no banco de dados com uma imagem padrão
        $sql = "INSERT INTO produto (produtos, valor, img) VALUES ('$produto', '$valor', '$img_padrao')";
        if (executarSQL($conexao, $sql)) {
            $id_produto = mysqli_insert_id($conexao);

            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileSize = $_FILES['file']['size'];
                $fileType = $_FILES['file']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = $id_produto . '.' . $fileExtension;

                $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = '../imgProduto';
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $updateSql = "UPDATE produto SET imagem = '$newFileName' WHERE id_produto = $id_produto";
                        executarSQL($conexao, $updateSql);

                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Imagem do produto atualizada com sucesso!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '../produto.php';
                            });
                        </script>";
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao mover o arquivo enviado',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '../produto.php';
                            });
                        </script>";
                    }
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Formato de arquivo não suportado',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../produto.php';
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Produto cadastrado com imagem padrão!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../produto.php';
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Falha ao cadastrar Produto.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '../produto.php';
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
                window.location.href = '../produto.php';
            });
        </script>";
    }
    ?>

    </body>
    </html>
