
<?php
session_start();
require_once "../../crud/function/conexao.php";
$conexao = conn();


// Verifica se a sessão está iniciada e se o usuário está logado
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: ../login.php");
    exit();
}

// Obtém o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Consulta SQL para obter os dados do usuário utilizando prepared statements para evitar injeção de SQL
$sql = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Verifica se a consulta foi bem-sucedida
if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo "Erro ao consultar o banco de dados: " . mysqli_error($conexao);
    exit();
}

// Obtém os dados do usuário
$dados = mysqli_fetch_assoc($resultado);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AL - Artes</title>
    <link rel="stylesheet" href="style.css" />

    <link rel="shortcut icon" href="https://i.postimg.cc/R07Wy2gJ/favicon.png" type="image/x-icon" />
</head>

<body>





    <header>
    <div class="header-top">
      <div class="container">
       

        <div class="header-alert-news">
          <p><b>BEM VINDO(A)</b> <?php echo htmlspecialchars($dados['nome']);?> </p>
        </div>

      
      </div>
    </div>

    <div class="header-main">
      <div class="container">
        <a href="#" class="header-logo"><img src="../../img/AL ARTES.jpg" alt="logo" width="120px" height="120px"
            height="36" /></a>

        <div class="header-search-container">
          <input type="search" name="search" class="search-field" placeholder="Digite o nome do seu produto" />

          <button class="search-btn">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </div>

        <div class="header-user-actions">
          <button class="action-btn">
          <img class="photo" src="../../imgPerfil/<?php echo htmlspecialchars($dados['perfil_img']); ?>" alt="Foto de perfil do usuário">
          </button>


       

        </div>
      </div>
    </div>



        <nav class="desktop-navigation-menu">
            <div class="container">
                <ul class="desktop-menu-category-list">
                    <li class="menu-category">
                        <a href="../index.php" class="menu-title">Home</a>
                    </li>

                    <li class="menu-category">
                        <a href="../index.php" class="menu-title">VOLTAR</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="mobile-bottom-navigation">
            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <button class="action-btn">
                <ion-icon name="bag-handle-outline"></ion-icon>
                <span class="count">0</span>
            </button>

            <button class="action-btn">
                <ion-icon name="home-outline"></ion-icon>
            </button>

            <button class="action-btn">
                <ion-icon name="heart-outline"></ion-icon>
                <span class="count">0</span>
            </button>

            <button class="action-btn" data-mobile-menu-open-btn>
                <ion-icon name="grid-outline"></ion-icon>
            </button>
        </div>

        <nav class="mobile-navigation-menu has-scrollbar" data-mobile-menu>
            <div class="menu-top">
                <h2 class="menu-title">Menu</h2>

                <button class="menu-close-btn" data-mobile-menu-close-btn>
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>

            <ul class="mobile-menu-category-list">
                <li class="menu-category"><a href="#" class="menu-title">Home</a></li>

                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Masculino</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Camisa</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Short e Jeans</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Calçados de Segurança</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Carteira</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Feminino</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Vestido e vestido</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Brincos</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Colar</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Kit de maquiagem</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Jewelyr</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Brincos</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Anéis de casal</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Colar</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Pulseiras</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-category">
                    <button class="accordion-menu" data-accordion-btn>
                        <p class="menu-title">Perfume</p>

                        <div>
                            <ion-icon name="add-outline" class="add-icon"></ion-icon>
                            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                        </div>
                    </button>

                    <ul class="submenu-category-list" data-accordion>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Roupas Perfume</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Desodorante</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Fragrância de flores</a>
                        </li>
                        <li class="submenu-category">
                            <a href="#" class="submenu-title">Ambientador</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-category"><a href="#" class="menu-title">Blog</a></li>

                <li class="menu-category">
                    <a href="#" class="menu-title">Ofertas quentes</a>
                </li>
            </ul>

            <div class="menu-bottom">
                <ul class="menu-category-list">
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title">Idioma</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>

                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Inglês</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Espanhol</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">Francês</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-category">
                        <button class="accordion-menu" data-accordion-btn>
                            <p class="menu-title">Moeda</p>
                            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
                        </button>

                        <ul class="submenu-category-list" data-accordion>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">USD &dollar;</a>
                            </li>
                            <li class="submenu-category">
                                <a href="#" class="submenu-title">EUR &euro;</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="menu-social-container">
                    <li>
                        <a href="#" class="social-link"><ion-icon name="logo-facebook"></ion-icon></a>
                    </li>
                    <li>
                        <a href="#" class="social-link"><ion-icon name="logo-twitter"></ion-icon></a>
                    </li>
                    <li>
                        <a href="#" class="social-link"><ion-icon name="logo-instagram"></ion-icon></a>
                    </li>
                    <li>
                        <a href="#" class="social-link"><ion-icon name="logo-linkedin"></ion-icon></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>




        <div class="product-container">
            <div class="container">
                <div class="sidebar has-scrollbar" data-mobile-menu>
                    <div class="sidebar-category">
                        <div class="sidebar-top">
                            <h2 class="sidebar-title">Categoria</h2>

                            <button class="sidebar-close-btn" data-mobile-menu-close-btn>
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>

                        <ul class="sidebar-menu-category-list">
                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/Xv9x15Q8/dress.png" alt="clothes" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Roupas</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Camisa</p>
                                            <data value="300" class="stock" title="Estoque disponível">300</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Shorts e jeans</p>
                                            <data value="60" class="stock" title="Estoque disponível">60</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Jaqueta</p>
                                            <data value="50" class="stock" title="Estoque disponível">50</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Vestido e vestido</p>
                                            <data value="87" class="stock" title="Estoque disponível">87</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/d3RBQZhB/shoes.png" alt="footwear" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Calçados</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Esportes</p>
                                            <data value="45" class="stock" title="Available Stock">45</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Formal</p>
                                            <data value="75" class="stock" title="Available Stock">75</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Casual</p>
                                            <data value="35" class="stock" title="Available Stock">35</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Calçados de segurança</p>
                                            <data value="26" class="stock" title="Available Stock">26</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/Y9HLrnY5/jewelry.png" alt="jewelyr" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Joia</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Earrings</p>
                                            <data value="46" class="stock" title="Available Stock">46</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Couple Rings</p>
                                            <data value="73" class="stock" title="Available Stock">73</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Necklace</p>
                                            <data value="61" class="stock" title="Available Stock">61</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/6q67R8Hz/perfume.png" alt="perfume" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Perfume</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Clothes Perfume</p>
                                            <data value="12" class="stock" title="Available Stock">12 pcs</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Deodorant</p>
                                            <data value="60" class="stock" title="Available Stock">60 pcs</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Jacket</p>
                                            <data value="50" class="stock" title="Available Stock">50 pcs</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Dress & Frock</p>
                                            <data value="87" class="stock" title="Available Stock">87 pcs</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/dQnZF91f/cosmetics.png" alt="cosmetics" class="menu-title-img"
                                            width="20" height="20" />
                                        <p class="menu-title">Cosmetics</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Shampoo</p>
                                            <data value="68" class="stock" title="Available Stock">68</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Sunscreen</p>
                                            <data value="46" class="stock" title="Available Stock">46</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Body Wash</p>
                                            <data value="79" class="stock" title="Available Stock">79</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Makeup Kit</p>
                                            <data value="23" class="stock" title="Available Stock">23</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/zBthxXZ7/glasses.png" alt="glasses" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Glasses</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Sunglasses</p>
                                            <data value="50" class="stock" title="Available Stock">50</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Lenses</p>
                                            <data value="48" class="stock" title="Available Stock">48</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-menu-category">
                                <button class="sidebar-accordion-menu" data-accordion-btn>
                                    <div class="menu-title-flex">
                                        <img src="https://i.postimg.cc/5yt0yZ0R/bag.png" alt="bags" class="menu-title-img" width="20"
                                            height="20" />
                                        <p class="menu-title">Bags</p>
                                    </div>

                                    <div>
                                        <ion-icon name="add-outline" class="add-icon"></ion-icon>
                                        <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
                                    </div>
                                </button>

                                <ul class="sidebar-submenu-category-list" data-accordion>
                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Shopping Bag</p>
                                            <data value="62" class="stock" title="Available Stock">62</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Gym Backpack</p>
                                            <data value="35" class="stock" title="Available Stock">35</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Purse</p>
                                            <data value="80" class="stock" title="Available Stock">80</data>
                                        </a>
                                    </li>

                                    <li class="sidebar-submenu-category">
                                        <a href="#" class="sidebar-submenu-title">
                                            <p class="product-name">Wallet</p>
                                            <data value="75" class="stock" title="Available Stock">75</data>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="product-showcase">
                        <h3 class="showcase-heading">Best Sellers</h3>

                        <div class="showcase-wrapper">
                            <div class="showcase-container">
                                <div class="showcase">
                                    <a href="#" class="showcase-img-box">
                                        <img src="https://i.postimg.cc/kGZn4GL2/1.jpg" alt="baby fabric shoes" class="showcase-img"
                                            width="75" height="75" />
                                    </a>

                                    <div class="showcase-content">
                                        <a href="#">
                                            <h4 class="showcase-title">Baby fabric shoes</h4>
                                        </a>

                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                        </div>

                                        <div class="price-box">
                                            <del>$5.00</del>
                                            <p class="price">$4.00</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase">
                                    <a href="#" class="showcase-img-box">
                                        <img src="https://i.postimg.cc/fySG8Kgb/2.jpg" alt="men's hoodies t-shirt" class="showcase-img"
                                            width="75" height="75" />
                                    </a>

                                    <div class="showcase-content">
                                        <a href="#">
                                            <h4 class="showcase-title">
                                                Men's hoodies t-shirt
                                            </h4>
                                        </a>

                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                        </div>

                                        <div class="price-box">
                                            <del>$17.00</del>
                                            <p class="price">$7.00</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase">
                                    <a href="#" class="showcase-img-box">
                                        <img src="https://i.postimg.cc/14xL2qLr/3.jpg" alt="girls t-shirt" class="showcase-img" width="75"
                                            height="75" />
                                    </a>

                                    <div class="showcase-content">
                                        <a href="#">
                                            <h4 class="showcase-title">Girls t-shirt</h4>
                                        </a>

                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                        </div>

                                        <div class="price-box">
                                            <del>$5.00</del>
                                            <p class="price">$3.00</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase">
                                    <a href="#" class="showcase-img-box">
                                        <img src="https://i.postimg.cc/y6wxsrKv/4.jpg" alt="woolen hat for men" class="showcase-img"
                                            width="75" height="75" />
                                    </a>

                                    <div class="showcase-content">
                                        <a href="#">
                                            <h4 class="showcase-title">Woolen hat for men</h4>
                                        </a>

                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                        </div>

                                        <div class="price-box">
                                            <del>$15.00</del>
                                            <p class="price">$12.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-box">
                    <div class="product-minimal">
                        <div class="product-showcase">
                            <h2 class="title">New Arrivals</h2>

                            <div class="showcase-wrapper has-scrollbar">
                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/fyLNm09z/clothes-1.jpg" alt="relaxed short full sleeve t-shirt"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Relaxed Short full sleeve t-shirt
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Clothes</a>

                                            <div class="price-box">
                                                <p class="price">$45.00</p>
                                                <del>$12.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/T3mXVxpD/clothes-2.jpg" alt="girls pink embro design top"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Girls pink Embro design top
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Clothes</a>

                                            <div class="price-box">
                                                <p class="price">$61.00</p>
                                                <del>$9.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/DzgH6wF8/clothes-3.jpg" alt="black floral wrap midi skirt"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Black Floral Wrap Midi Skirt
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Clothes</a>

                                            <div class="price-box">
                                                <p class="price">$76.00</p>
                                                <del>$25.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/02w43fPg/shirt-1.jpg" alt="pure garment dyed cotton shirt"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Pure Garment Dyed Cotton Shirt
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Men's Fashion</a>

                                            <div class="price-box">
                                                <p class="price">$68.00</p>
                                                <del>$31.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/DZ3QSqRG/jacket-5.jpg" alt="relaxed short full sleeve t-shirt"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Relaxed Short full sleeve t-shirt
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Clothes</a>

                                            <div class="price-box">
                                                <p class="price">$45.00</p>
                                                <del>$12.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/9fnSKNRh/jacket-1.jpg" alt="men yarn fleece full-zip jacket"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Men Yarn Fleece Full-zip Jacket
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Winter wear</a>

                                            <div class="price-box">
                                                <p class="price">$61.00</p>
                                                <del>$11.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/jdybNKWJ/jacket-3.jpg" alt="mens winter leathers jackets"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Mens Winter Leathers Jackets
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Jackets</a>

                                            <div class="price-box">
                                                <p class="price">$50.00</p>
                                                <del>$25.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/7Lmt7tMz/shorts-1.jpg" alt="better basics french terry sweatshorts"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Better Basics French Terry Sweatshorts
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Shorts</a>

                                            <div class="price-box">
                                                <p class="price">$20.00</p>
                                                <del>$10.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-showcase">
                            <h2 class="title">Trending</h2>

                            <div class="showcase-wrapper has-scrollbar">
                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/pLWhzrLm/sports-1.jpg" alt="running & trekking shoes - white"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Running & Trekking Shoes - White
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Sports</a>

                                            <div class="price-box">
                                                <p class="price">$49.00</p>
                                                <del>$15.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/DfjFzzbv/sports-2.jpg" alt="trekking & running shoes - black"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Trekking & Running Shoes - Black
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Sports</a>

                                            <div class="price-box">
                                                <p class="price">$78.00</p>
                                                <del>$36.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/qRPjQYmZ/party-wear-1.jpg" alt="womens party wear shoes"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Womens Party Wear Shoes
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Party Wear</a>

                                            <div class="price-box">
                                                <p class="price">$94.00</p>
                                                <del>$42.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/cH1M4Wv3/sports-3.jpg" alt="sports claw women's shoes"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Sports Claw Women's Shoes
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Sports</a>

                                            <div class="price-box">
                                                <p class="price">$54.00</p>
                                                <del>$65.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/JnczQTWc/sports-6.jpg" alt="air tekking shoes - white"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Air Trekking Shoes - White
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Sports</a>

                                            <div class="price-box">
                                                <p class="price">$52.00</p>
                                                <del>$55.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/XvxVGrKQ/shoe-3.jpg" alt="Boot With Suede Detail"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Boot With Suede Detail
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Boots</a>

                                            <div class="price-box">
                                                <p class="price">$20.00</p>
                                                <del>$30.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/JnMtkwB5/shoe-1.jpg" alt="men's leather formal wear shoes"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Men's Leather Formal Wear Shoes
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Formal</a>

                                            <div class="price-box">
                                                <p class="price">$56.00</p>
                                                <del>$78.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/0yCHGD6R/shoe-2.jpg" alt="casual men's brown shoes"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Casual Men's Brown Shoes
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Casual</a>

                                            <div class="price-box">
                                                <p class="price">$50.00</p>
                                                <del>$55.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-showcase">
                            <h2 class="title">Top Rated</h2>

                            <div class="showcase-wrapper has-scrollbar">
                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/jq84QT45/watch-3.jpg" alt="pocket watch leather pouch"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Pocket Watch Leather Pouch
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Watches</a>

                                            <div class="price-box">
                                                <p class="price">$50.00</p>
                                                <del>$34.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/MZmBYvv7/jewellery-3.jpg" alt="silver deer heart necklace"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Silver Deer Heart Necklace
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Jewellery</a>

                                            <div class="price-box">
                                                <p class="price">$84.00</p>
                                                <del>$30.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/R0Kv9Jtq/perfume.jpg" alt="titan 100 ml womens perfume"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Titan 100 Ml Womens Perfume
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Perfume</a>

                                            <div class="price-box">
                                                <p class="price">$42.00</p>
                                                <del>$10.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/jj4kzynp/belt.jpg" alt="men's leather reversible belt"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Men's Leather Reversible Belt
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Belt</a>

                                            <div class="price-box">
                                                <p class="price">$24.00</p>
                                                <del>$10.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="showcase-container">
                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/T24Nqdh3/jewellery-2.jpg" alt="platinum zircon classic ring"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Platinum Zircon Classic Ring
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Jewellery</a>

                                            <div class="price-box">
                                                <p class="price">$62.00</p>
                                                <del>$65.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/rsk1gH6g/watch-1.jpg" alt="smart watche vital plus"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Smart Watch Vital Plus
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Watches</a>

                                            <div class="price-box">
                                                <p class="price">$56.00</p>
                                                <del>$78.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/wjGDnM81/shampoo.jpg" alt="shampoo conditioner packs"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Shampoo Conditioner Packs
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Cosmetics</a>

                                            <div class="price-box">
                                                <p class="price">$20.00</p>
                                                <del>$30.00</del>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="showcase">
                                        <a href="#" class="showcase-img-box">
                                            <img src="https://i.postimg.cc/6qd3mpCv/jewellery-1.jpg" alt="rose gold peacock earrings"
                                                class="showcase-img" width="70" />
                                        </a>

                                        <div class="showcase-content">
                                            <a href="#">
                                                <h4 class="showcase-title">
                                                    Rose Gold Peacock Earrings
                                                </h4>
                                            </a>
                                            <a href="#" class="showcase-category">Jewellery</a>

                                            <div class="price-box">
                                                <p class="price">$20.00</p>
                                                <del>$30.00</del>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-featured">
                        <h2 class="title">Deal of the day</h2>

                        <div class="showcase-wrapper has-scrollbar">
                            <div class="showcase-container">
                                <div class="showcase">
                                    <div class="showcase-banner">
                                        <img src="https://i.postimg.cc/wjGDnM81/shampoo.jpg" alt="shampoo, conditioner & facewash packs"
                                            class="showcase-img" />
                                    </div>

                                    <div class="showcase-content">
                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star-outline"></ion-icon>
                                            <ion-icon name="star-outline"></ion-icon>
                                        </div>

                                        <a href="#">
                                            <h3 class="showcase-title">
                                                SHAMPOO, CONDITIONER & FACEWASH PACKS
                                            </h3>
                                        </a>
                                        <p class="showcase-desc">
                                            Old Spice includes a variety of products designed for
                                            men's grooming needs, such as deodorants and
                                            antiperspirants, body washes, shaving creams,
                                            aftershaves and hair and beard care
                                        </p>

                                        <div class="price-box">
                                            <p class="price">$150.00</p>
                                            <del>$200.00</del>
                                        </div>

                                        <button class="add-cart-btn">Add to Cart</button>

                                        <div class="showcase-status">
                                            <div class="wrapper">
                                                <p>Already Sold: <b>20</b></p>
                                                <p>Available: <b>40</b></p>
                                            </div>

                                            <div class="showcase-status-bar"></div>
                                        </div>

                                        <div class="countdown-box">
                                            <p class="countdown-desc">Hurry up! Offer ends in:</p>

                                            <div class="countdown">
                                                <div class="countdown-content">
                                                    <p class="display-number">360</p>
                                                    <p class="display-text">Days</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">24</p>
                                                    <p class="display-text">Hours</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">59</p>
                                                    <p class="display-text">Min</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">00</p>
                                                    <p class="display-text">Sec</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="showcase-container">
                                <div class="showcase">
                                    <div class="showcase-banner">
                                        <img src="https://i.postimg.cc/6qd3mpCv/jewellery-1.jpg" alt="Rose Gold diamonds Earring"
                                            class="showcase-img" />
                                    </div>

                                    <div class="showcase-content">
                                        <div class="showcase-rating">
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star"></ion-icon>
                                            <ion-icon name="star-outline"></ion-icon>
                                            <ion-icon name="star-outline"></ion-icon>
                                        </div>

                                        <a href="#">
                                            <h3 class="showcase-title">
                                                ROSE GOLD DIAMOND EARRINGS
                                            </h3>
                                        </a>
                                        <p class="showcase-desc">
                                            It's a stylish piece of jewelry that combines the warm
                                            tones of rose gold with the brilliance of diamonds.
                                            Enjoy a a luxurious and elegant accessory, perfect for
                                            enhancing any outfit while adding a touch of
                                            sophistication
                                        </p>

                                        <div class="price-box">
                                            <p class="price">$1990.00</p>
                                            <del>$2000.00</del>
                                        </div>

                                        <button class="add-cart-btn">Add to Cart</button>

                                        <div class="showcase-status">
                                            <div class="wrapper">
                                                <p>Already Sold: <b>15</b></p>
                                                <p>Available: <b>40</b></p>
                                            </div>

                                            <div class="showcase-status-bar"></div>
                                        </div>

                                        <div class="countdown-box">
                                            <p class="countdown-desc">Hurry up! Offer ends in:</p>

                                            <div class="countdown">
                                                <div class="countdown-content">
                                                    <p class="display-number">360</p>
                                                    <p class="display-text">Days</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">24</p>
                                                    <p class="display-text">Hours</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">59</p>
                                                    <p class="display-text">Min</p>
                                                </div>

                                                <div class="countdown-content">
                                                    <p class="display-number">00</p>
                                                    <p class="display-text">Sec</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-main">
                        <h2 class="title">Novos Produtos</h2>

                        <div class="product-grid">
                            <?php
                            // Consulta para buscar os produtos
                            $sql = "SELECT * FROM produtos ORDER BY data_cadastro DESC LIMIT 12";
                            $result = $conexao->query($sql);

                            // Verifica se há resultados
                            if ($result && $result->num_rows > 0) {
                                // Loop através dos resultados
                                while ($produto = $result->fetch_assoc()) {
                                    // Formatação de preços
                                    $precoOriginal = number_format($produto['preco_original'], 2, ',', '.');
                                    $precoPromocional = number_format($produto['preco_promocional'], 2, ',', '.');
                                    $desconto = ($produto['desconto'] > 0) ? $produto['desconto'] . '%' : null;

                                    // Tratamento das imagens (caminhos relativos)
                                    $imgPrincipal = !empty($produto['imagem_principal']) ? '../../imgProdutos/' . $produto['imagem_principal'] : 'img/sem-imagem.jpg';
                                    $imgHover = !empty($produto['imagem_hover']) ? '../../imgProdutos/' . $produto['imagem_hover'] : $imgPrincipal;
                            ?>
                                    <div class="showcase">
                                        <div class="showcase-banner">
                                            <!-- Imagem principal -->
                                            <img src="<?= $imgPrincipal ?>"
                                                alt="<?= htmlspecialchars($produto['nome']) ?>"
                                                class="product-img default" width="300" loading="lazy" />

                                            <!-- Imagem hover -->
                                            <img src="<?= $imgHover ?>"
                                                alt="<?= htmlspecialchars($produto['nome']) ?>"
                                                class="product-img hover" width="300" loading="lazy" />

                                            <?php if ($desconto): ?>
                                                <p class="showcase-badge"><?= $desconto ?></p>
                                            <?php endif; ?>

                                            <div class="showcase-actions">
                                                <div class="showcase-actions">
                                                    <button class="btn-action" aria-label="Adicionar aos favoritos" onclick="toggleWishlist(this, <?= $produto['id_produto'] ?>)">
                                                        <ion-icon name="heart-outline"></ion-icon>
                                                    </button>
                                                    <button class="btn-action" aria-label="Visualizar detalhes" onclick="viewProductDetails(<?= $produto['id_produto'] ?>)">
                                                        <ion-icon name="eye-outline"></ion-icon>
                                                    </button>
                                                    <button class="btn-action" aria-label="Comparar produto" onclick="addToCompare(<?= $produto['id_produto'] ?>)">
                                                        <ion-icon name="repeat-outline"></ion-icon>
                                                    </button>
                                                    <button class="btn-action" aria-label="Adicionar ao carrinho" onclick="addToCart(<?= $produto['id_produto'] ?>)">
                                                        <ion-icon name="bag-add-outline"></ion-icon>
                                                    </button>
                                                    <script src="script.js"></script>
                                                    <link rel="stylesheet" href="style_carrinho.css">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="showcase-content">
                                            <a href="categoria.php?cat=<?= urlencode($produto['categoria']) ?>" class="showcase-category">
                                                <?= strtoupper(htmlspecialchars($produto['categoria'])) ?>
                                            </a>
                                            <a href="produto.php?id=<?= $produto['id_produto'] ?>">
                                                <h3 class="showcase-title">
                                                    <?= htmlspecialchars($produto['nome']) ?>
                                                </h3>
                                            </a>

                                            <div class="showcase-rating" aria-label="Avaliação: <?= $produto['avaliacao'] ?> estrelas">
                                                <?php
                                                $rating = round($produto['avaliacao']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $rating
                                                        ? '<ion-icon name="star" aria-hidden="true"></ion-icon>'
                                                        : '<ion-icon name="star-outline" aria-hidden="true"></ion-icon>';
                                                }
                                                ?>
                                            </div>

                                            <div class="price-box">
                                                <p class="price">R$ <?= $precoPromocional ?></p>
                                                <?php if ($precoOriginal != $precoPromocional): ?>
                                                    <del>R$ <?= $precoOriginal ?></del>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<div class="empty-message">Nenhum produto encontrado.</div>';
                            }

                            // Fecha a conexão
                            if (isset($conexao)) {
                                $conexao->close();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </main>

    <footer>
        <div class="footer-category">
            <div class="container">
                <h2 class="footer-category-title">Diretório de Marcas</h2>

                <div class="footer-category-box">
                    <h3 class="category-box-title">Moda:</h3>

                    <a href="#" class="footer-category-link">Camisetas</a>
                    <a href="#" class="footer-category-link">Camisas</a>
                    <a href="#" class="footer-category-link">Shorts e Jeans</a>
                    <a href="#" class="footer-category-link">Jaquetas</a>
                    <a href="#" class="footer-category-link">Vestidos</a>
                    <a href="#" class="footer-category-link">Roupas íntimas</a>
                    <a href="#" class="footer-category-link">Meias</a>
                </div>

                <div class="footer-category-box">
                    <h3 class="category-box-title">Calçados:</h3>

                    <a href="#" class="footer-category-link">Esportivo</a>
                    <a href="#" class="footer-category-link">Social</a>
                    <a href="#" class="footer-category-link">Botas</a>
                    <a href="#" class="footer-category-link">Casual</a>
                    <a href="#" class="footer-category-link">Sapatos cowboy</a>
                    <a href="#" class="footer-category-link">Sapatos de segurança</a>
                    <a href="#" class="footer-category-link">Sapatos para festas</a>
                    <a href="#" class="footer-category-link">Marcas</a>
                    <a href="#" class="footer-category-link">Réplicas</a>
                    <a href="#" class="footer-category-link">Sapatos alongados</a>
                </div>

                <div class="footer-category-box">
                    <h3 class="category-box-title">Joias:</h3>

                    <a href="#" class="footer-category-link">Colares</a>
                    <a href="#" class="footer-category-link">Brincos</a>
                    <a href="#" class="footer-category-link">Alianças</a>
                    <a href="#" class="footer-category-link">Pingentes</a>
                    <a href="#" class="footer-category-link">Cristais</a>
                    <a href="#" class="footer-category-link">Pulseiras</a>
                    <a href="#" class="footer-category-link">Braceletes</a>
                    <a href="#" class="footer-category-link">Piercings</a>
                    <a href="#" class="footer-category-link">Correntes</a>
                </div>

                <div class="footer-category-box">
                    <h3 class="category-box-title">Cosméticos:</h3>

                    <a href="#" class="footer-category-link">Xampu</a>
                    <a href="#" class="footer-category-link">Sabonete líquido</a>
                    <a href="#" class="footer-category-link">Sabonete facial</a>
                    <a href="#" class="footer-category-link">Kit de maquiagem</a>
                    <a href="#" class="footer-category-link">Lápis de olho</a>
                    <a href="#" class="footer-category-link">Batom</a>
                    <a href="#" class="footer-category-link">Perfume</a>
                    <a href="#" class="footer-category-link">Sabonete corporal</a>
                    <a href="#" class="footer-category-link">Esmalte</a>
                    <a href="#" class="footer-category-link">Gel para cabelo</a>
                    <a href="#" class="footer-category-link">Tintura para cabelo</a>
                    <a href="#" class="footer-category-link">Descolorante</a>
                    <a href="#" class="footer-category-link">Protetor solar</a>
                    <a href="#" class="footer-category-link">Loção para pele</a>
                </div>
            </div>
        </div>

        <div class="footer-nav">
            <div class="container">
                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Categorias Populares</h2>
                    </li>

                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Moda</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Eletrônicos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Cosméticos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Saúde</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Relógios</a>
                    </li>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Produtos</h2>
                    </li>

                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Moda</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Eletrônicos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Cosméticos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Saúde</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Relógios</a>
                    </li>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Nossa Empresa</h2>
                    </li>

                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Entrega</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Aviso Legal</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Termos e Condições</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Sobre nós</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Pagamento Seguro</a>
                    </li>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Serviços</h2>
                    </li>

                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Queda de preços</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Novos produtos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Melhores vendas</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Contate-nos</a>
                    </li>
                    <li class="footer-nav-item">
                        <a href="#" class="footer-nav-link">Mapa do site</a>
                    </li>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Contato</h2>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>

                        <address class="content">
                            COHAB II Q19 CASA 302 URUGUAINA RS - BRASIL
                        </address>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>

                        <a href="tel:+607936-8058" class="footer-nav-link">+55(55) 99998-2163 </a>
                    </li>

                    <li class="footer-nav-item flex">
                        <div class="icon-box">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>

                        <a href="mailto:example@gmail.com" class="footer-nav-link">AL_artes@gmail.com</a>
                    </li>
                </ul>

                <ul class="footer-nav-list">
                    <li class="footer-nav-item">
                        <h2 class="nav-title">Siga-nos</h2>
                    </li>

                    <li>
                        <ul class="social-link">
                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link"><ion-icon name="logo-facebook"></ion-icon></a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link"><ion-icon name="logo-twitter"></ion-icon></a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link"><ion-icon name="logo-linkedin"></ion-icon></a>
                            </li>

                            <li class="footer-nav-item">
                                <a href="#" class="footer-nav-link"><ion-icon name="logo-instagram"></ion-icon></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">

                <p class="copyright">
                    Copyright &copy; <a href="#">AL-ARTES</a> todos os direitos reservados
                </p>
            </div>
        </div>
    </footer>

    <script src="main.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>