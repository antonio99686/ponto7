-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 02/05/2025 às 23:17
-- Versão do servidor: 8.3.0
-- Versão do PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mattes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinhos`
--

DROP TABLE IF EXISTS `carrinhos`;
CREATE TABLE IF NOT EXISTS `carrinhos` (
  `id` varchar(32) NOT NULL,
  `dados` text NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `carrinhos`
--

INSERT INTO `carrinhos` (`id`, `dados`, `data_criacao`, `data_modificacao`) VALUES
('cart_abc123', '[{\"id\":2,\"name\":\"Calça Jeans Slim\",\"price\":129.90,\"image\":\"calca_jeans.jpg\",\"quantity\":1},{\"id\":5,\"name\":\"Relógio Digital\",\"price\":59.90,\"image\":\"relogio.jpg\",\"quantity\":2}]', '2025-05-02 21:36:54', '2025-05-02 21:36:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `imagem_principal` varchar(255) DEFAULT NULL,
  `imagem_hover` varchar(255) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `estoque` int DEFAULT '0',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `preco_original` double(10,0) NOT NULL,
  `desconto` double NOT NULL,
  `preco_promocional` double NOT NULL,
  `avaliacao` float NOT NULL,
  PRIMARY KEY (`id_produto`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `imagem_principal`, `imagem_hover`, `categoria`, `estoque`, `data_cadastro`, `preco_original`, `desconto`, `preco_promocional`, `avaliacao`) VALUES
(1, 'Camiseta Básica Branca', 'Camiseta 100% algodão, cor branca', 'croche femenino.jpeg', 'croche masculino.jpg', 'Camisetas', 50, '2025-05-02 21:36:22', 800, 877, 85, 5),
(2, 'Calça Jeans Slim', 'Calça jeans masculina modelo slim', 'croche femenino.jpeg', 'croche masculino.jpg', 'Calças', 30, '2025-05-02 21:36:22', 595, 277, 7852, 5),
(3, 'Tênis Esportivo', 'Tênis para corrida com amortecimento', 'croche femenino.jpeg', 'croche masculino.jpg', 'Acessórios', 20, '2025-05-02 21:36:22', 955, 632, 7, 5),
(4, 'Moletom Capuz', 'Moletom com capuz e bolso canguru', 'croche femenino.jpeg', 'croche masculino.jpg', 'Camisetas', 35, '2025-05-02 21:36:22', 99, 862, 54, 5),
(5, 'Relógio Digital', 'Relógio digital resistente à água', 'croche femenino.jpeg', 'croche masculino.jpg', 'Acessórios', 15, '2025-05-02 21:36:22', 5595, 752, 74, 2),
(6, 'Camisa Social Azul', 'Camisa social manga longa azul', 'croche femenino.jpeg', 'croche masculino.jpg', 'Camisetas', 25, '2025-05-02 21:36:22', 5929, 752, 754, 3),
(7, 'Shorts Esportivo', 'Shorts para prática esportiva', 'croche femenino.jpeg', 'croche masculino.jpg', 'Calças', 40, '2025-05-02 21:36:22', 556, 655, 744, 1),
(8, 'Boné Aba Curva', 'Boné ajustável com aba curva', 'croche femenino.jpeg', 'croche masculino.jpg', 'Acessórios', 60, '2025-05-02 21:36:22', 265, 888, 84, 0),
(9, 'Blusa de Frio', 'Blusa de frio com zíper', 'croche femenino.jpeg', 'croche masculino.jpg', 'Camisetas', 18, '2025-05-02 21:36:22', 5956, 555, 952, 5),
(10, 'Cinto de Couro', 'Cinto masculino de couro legítimo', 'croche femenino.jpeg', 'croche masculino.jpg', 'Acessórios', 30, '2025-05-02 21:36:22', 4985, 88, 744, 4),
(11, 'Camiseta Estampada', 'Camiseta com estampa exclusiva', 'croche femenino.jpeg', 'croche masculino.jpg', 'Camisetas', 45, '2025-05-02 21:36:22', 8, 888, 8521, 2),
(12, 'Calça Moletom', 'Calça de moletom confortável', 'croche femenino.jpeg', 'croche masculino.jpg', 'Calças', 28, '2025-05-02 21:36:22', 5916, 556, 42, 4),
(13, 'rwerwer', 'rwerwe', 'produto_padrao.jpg', 'produto_padrao.jpg', 'erew', 3, '2025-05-02 22:56:21', 545, 3, 334, 4),
(14, '', '', 'produto_padrao.jpg', 'produto_padrao.jpg', '', 0, '2025-05-02 23:01:11', 0, 0, 0, 0),
(15, 'wer', '55859', 'padrao_principal.jpg', 'padrao_hover.jpg', 'rwer', 5, '2025-05-03 02:06:53', 888, 311, -1873.68, 5),
(16, 'rwerwer', 'iiiii', '44b6084634686f20df4255152f063092_principal.jpg', 'padrao_hover.jpg', 'rwer', 4, '2025-05-03 02:14:09', 55555, 444, -191109.2, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `senha` varchar(225) NOT NULL,
  `cpf` char(14) NOT NULL,
  `perfil_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `senha`, `cpf`, `perfil_img`) VALUES
(1, 'antonio carlos mattes mongelo', '123', '05500840029', 'luce.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
