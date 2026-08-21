-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21/08/2026 às 01:31
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

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
-- Estrutura para tabela `avaliacoes`
--

DROP TABLE IF EXISTS `avaliacoes`;
CREATE TABLE IF NOT EXISTS `avaliacoes` (
  `id_avaliacao` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_produto` int NOT NULL,
  `id_pedido` int DEFAULT NULL,
  `nota` int NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `comentario` text,
  `imagens` json DEFAULT NULL,
  `aprovado` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_avaliacao`),
  KEY `id_pedido` (`id_pedido`),
  KEY `idx_produto` (`id_produto`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_nota` (`nota`)
) ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinhos`
--

DROP TABLE IF EXISTS `carrinhos`;
CREATE TABLE IF NOT EXISTS `carrinhos` (
  `id_carrinho` char(36) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `sessao_id` varchar(100) DEFAULT NULL,
  `dados` json NOT NULL,
  `subtotal` decimal(10,2) DEFAULT '0.00',
  `desconto_total` decimal(10,2) DEFAULT '0.00',
  `frete` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) DEFAULT '0.00',
  `cep_entrega` varchar(10) DEFAULT NULL,
  `cupom_desconto` varchar(50) DEFAULT NULL,
  `status` enum('ativo','abandonado','convertido') DEFAULT 'ativo',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modificacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_abandono` datetime DEFAULT NULL,
  PRIMARY KEY (`id_carrinho`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_sessao` (`sessao_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_modificacao` (`data_modificacao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `descricao` text,
  `slug` varchar(50) DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `categoria_pai_id` int DEFAULT NULL,
  `ordem` int DEFAULT '0',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_pai` (`categoria_pai_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome`, `descricao`, `slug`, `icone`, `categoria_pai_id`, `ordem`, `status`, `data_cadastro`) VALUES
(1, 'Ferramentas Manuais', 'Martelos, chaves, alicates e mais', 'ferramentas-manuais', '🔧', NULL, 0, 'ativo', '2026-08-21 00:39:12'),
(2, 'Ferramentas Elétricas', 'Furadeiras, serras, lixadeiras', 'ferramentas-eletricas', '⚡', NULL, 0, 'ativo', '2026-08-21 00:39:12'),
(3, 'Materiais de Construção', 'Cimento, areia, tijolos', 'materiais-construcao', '🧱', NULL, 0, 'ativo', '2026-08-21 00:39:12'),
(4, 'Tintas e Acabamentos', 'Tintas, vernizes, massas', 'tintas-acabamentos', '🎨', NULL, 0, 'ativo', '2026-08-21 00:39:12'),
(5, 'Ferramentas de Medição', 'Trenas, níveis, metros', 'ferramentas-medicao', '📏', NULL, 0, 'ativo', '2026-08-21 00:39:12'),
(6, 'Segurança e EPI', 'Capacetes, luvas, óculos', 'segurança-epi', '⛑️', NULL, 0, 'ativo', '2026-08-21 00:39:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupons`
--

DROP TABLE IF EXISTS `cupons`;
CREATE TABLE IF NOT EXISTS `cupons` (
  `id_cupom` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tipo_desconto` enum('percentual','fixo') DEFAULT 'percentual',
  `valor_desconto` decimal(10,2) NOT NULL,
  `valor_minimo_pedido` decimal(10,2) DEFAULT '0.00',
  `uso_unico` tinyint(1) DEFAULT '0',
  `uso_por_usuario` int DEFAULT '1',
  `uso_total` int DEFAULT '1',
  `uso_atual` int DEFAULT '0',
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `status` enum('ativo','inativo','expirado') DEFAULT 'ativo',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cupom`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_codigo` (`codigo`),
  KEY `idx_status` (`status`),
  KEY `idx_datas` (`data_inicio`,`data_fim`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

DROP TABLE IF EXISTS `enderecos`;
CREATE TABLE IF NOT EXISTS `enderecos` (
  `id_endereco` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo_endereco` enum('cobranca','entrega','faturamento') DEFAULT 'entrega',
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` char(2) NOT NULL,
  `pais` varchar(30) DEFAULT 'Brasil',
  `referencia` varchar(100) DEFAULT NULL,
  `principal` tinyint(1) DEFAULT '0',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_endereco`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_principal` (`principal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_precos`
--

DROP TABLE IF EXISTS `historico_precos`;
CREATE TABLE IF NOT EXISTS `historico_precos` (
  `id_historico` int NOT NULL AUTO_INCREMENT,
  `id_produto` int NOT NULL,
  `preco_antigo` decimal(10,2) NOT NULL,
  `preco_novo` decimal(10,2) NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alterado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_historico`),
  KEY `idx_produto` (`id_produto`),
  KEY `idx_data` (`data_alteracao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

DROP TABLE IF EXISTS `itens_pedido`;
CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_produto` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `desconto_item` decimal(10,2) DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `personalizacao` json DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `idx_pedido` (`id_pedido`),
  KEY `idx_produto` (`id_produto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_auditoria`
--

DROP TABLE IF EXISTS `logs_auditoria`;
CREATE TABLE IF NOT EXISTS `logs_auditoria` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `acao` varchar(50) NOT NULL,
  `tabela` varchar(50) NOT NULL,
  `registro_id` int DEFAULT NULL,
  `dados_antigos` json DEFAULT NULL,
  `dados_novos` json DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `data_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_tabela` (`tabela`),
  KEY `idx_data` (`data_registro`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_endereco_entrega` int DEFAULT NULL,
  `id_endereco_cobranca` int DEFAULT NULL,
  `numero_pedido` varchar(20) NOT NULL,
  `status_pedido` enum('pendente','pago','processando','enviado','entregue','cancelado','devolvido') DEFAULT 'pendente',
  `status_pagamento` enum('aguardando','aprovado','recusado','estornado') DEFAULT 'aguardando',
  `subtotal` decimal(10,2) NOT NULL,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `frete` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `cupom_desconto` varchar(50) DEFAULT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `parcelas` int DEFAULT '1',
  `observacoes` text,
  `rastreio_codigo` varchar(50) DEFAULT NULL,
  `data_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_pagamento` datetime DEFAULT NULL,
  `data_processamento` datetime DEFAULT NULL,
  `data_envio` datetime DEFAULT NULL,
  `data_entrega` datetime DEFAULT NULL,
  `data_cancelamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  UNIQUE KEY `numero_pedido` (`numero_pedido`),
  KEY `id_endereco_entrega` (`id_endereco_entrega`),
  KEY `id_endereco_cobranca` (`id_endereco_cobranca`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_status` (`status_pedido`),
  KEY `idx_numero` (`numero_pedido`),
  KEY `idx_data` (`data_pedido`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `sku` varchar(50) NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `subcategoria` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `unidade_medida` varchar(20) DEFAULT 'un',
  `peso_kg` decimal(8,3) DEFAULT '0.000',
  `dimensoes` varchar(100) DEFAULT NULL,
  `imagem_principal` varchar(255) DEFAULT NULL,
  `imagem_hover` varchar(255) DEFAULT NULL,
  `imagens_galeria` json DEFAULT NULL,
  `preco_custo` decimal(10,2) DEFAULT NULL,
  `preco_venda` decimal(10,2) NOT NULL,
  `preco_promocional` decimal(10,2) DEFAULT NULL,
  `desconto_percentual` decimal(5,2) DEFAULT '0.00',
  `data_inicio_promocao` datetime DEFAULT NULL,
  `data_fim_promocao` datetime DEFAULT NULL,
  `estoque_atual` int NOT NULL DEFAULT '0',
  `estoque_minimo` int DEFAULT '5',
  `estoque_maximo` int DEFAULT '100',
  `localizacao_estoque` varchar(50) DEFAULT NULL,
  `status` enum('ativo','inativo','rascunho','esgotado') DEFAULT 'ativo',
  `destaque` tinyint(1) DEFAULT '0',
  `novo` tinyint(1) DEFAULT '0',
  `avaliacao_media` decimal(3,2) DEFAULT '0.00',
  `total_avaliacoes` int DEFAULT '0',
  `meta_titulo` varchar(100) DEFAULT NULL,
  `meta_descricao` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `criado_por` int DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_categoria` (`categoria_id`),
  KEY `idx_status` (`status`),
  KEY `idx_destaque` (`destaque`),
  KEY `idx_preco` (`preco_venda`),
  KEY `idx_estoque` (`estoque_atual`),
  KEY `idx_slug` (`slug`),
  KEY `idx_sku` (`sku`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `sku`, `categoria_id`, `subcategoria`, `marca`, `unidade_medida`, `peso_kg`, `dimensoes`, `imagem_principal`, `imagem_hover`, `imagens_galeria`, `preco_custo`, `preco_venda`, `preco_promocional`, `desconto_percentual`, `data_inicio_promocao`, `data_fim_promocao`, `estoque_atual`, `estoque_minimo`, `estoque_maximo`, `localizacao_estoque`, `status`, `destaque`, `novo`, `avaliacao_media`, `total_avaliacoes`, `meta_titulo`, `meta_descricao`, `slug`, `data_cadastro`, `data_atualizacao`, `criado_por`) VALUES
(2, 'Furadeira de Impacto 600W', 'Furadeira com 600W de potência, 3 funções, mandril 13mm', 'FER-002', 2, NULL, 'Bosch', 'un', 0.000, NULL, NULL, NULL, NULL, 150.00, 220.00, NULL, 0.00, NULL, NULL, 15, 5, 100, NULL, 'ativo', 1, 1, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(3, 'Caixa de Parafuso 4x40 (100un)', 'Parafusos autoatarrachantes com fenda Philips', 'FER-003', 1, NULL, 'Ciser', 'un', 0.000, NULL, NULL, NULL, NULL, 10.00, 18.50, NULL, 0.00, NULL, NULL, 200, 5, 100, NULL, 'ativo', 0, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(4, 'Trena a Laser 40m', 'Trena digital com alcance de 40 metros, precisão de 1mm', 'FER-004', 5, NULL, 'Stanley', 'un', 0.000, NULL, NULL, NULL, NULL, 95.00, 150.00, NULL, 0.00, NULL, NULL, 8, 5, 100, NULL, 'ativo', 1, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(5, 'Lata de Tinta Acrílica 18L', 'Tinta acrílica premium, alto desempenho, acabamento fosco', 'FER-005', 4, NULL, 'Suvinil', 'un', 0.000, NULL, NULL, NULL, NULL, 200.00, 280.00, NULL, 0.00, NULL, NULL, 12, 5, 100, NULL, 'ativo', 1, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(6, 'Disco de Corte para Aço', 'Disco de corte para aço inoxidável, 4\" x 1/16\"', 'FER-006', 2, NULL, 'Norton', 'un', 0.000, NULL, NULL, NULL, NULL, 5.00, 8.00, NULL, 0.00, NULL, NULL, 120, 5, 100, NULL, 'ativo', 0, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(7, 'Chave de Fenda Conjunto 6 Peças', 'Kit com chaves de fenda de diversos tamanhos', 'FER-007', 1, NULL, 'Gedore', 'un', 0.000, NULL, NULL, NULL, NULL, 30.00, 45.00, NULL, 0.00, NULL, NULL, 30, 5, 100, NULL, 'ativo', 0, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(8, 'Nível de Bolha 1m', 'Nível de bolha com precisão de 0.5mm/m', 'FER-008', 5, NULL, 'Starrett', 'un', 0.000, NULL, NULL, NULL, NULL, 22.00, 35.00, NULL, 0.00, NULL, NULL, 25, 5, 100, NULL, 'ativo', 0, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:39:12', '2026-08-21 00:39:12', NULL),
(9, 'adasd', 'asdasda', 'ADASD', NULL, NULL, NULL, 'un', 0.000, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, 0.00, NULL, NULL, 5, 5, 100, NULL, 'ativo', 0, 0, 0.00, 0, NULL, NULL, NULL, '2026-08-21 00:58:15', '2026-08-21 00:58:15', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `cpf` char(14) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('M','F','Outro','Prefiro não informar') DEFAULT NULL,
  `perfil_img` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('admin','vendedor','cliente') DEFAULT 'cliente',
  `status` enum('ativo','inativo','bloqueado') DEFAULT 'ativo',
  `receber_ofertas` tinyint(1) DEFAULT '1',
  `receber_newsletter` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ultimo_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `idx_email` (`email`),
  KEY `idx_cpf` (`cpf`),
  KEY `idx_status` (`status`),
  KEY `idx_tipo` (`tipo_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome_completo`, `email`, `senha_hash`, `cpf`, `telefone`, `data_nascimento`, `genero`, `perfil_img`, `tipo_usuario`, `status`, `receber_ofertas`, `receber_newsletter`, `data_cadastro`, `data_atualizacao`, `ultimo_login`) VALUES
(1, 'Administrador', 'antonio.05500@aluno.iffar.edu.br\n', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123.456.789-00', NULL, NULL, NULL, NULL, 'admin', 'ativo', 1, 1, '2026-08-21 00:39:12', '2026-08-21 00:40:43', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos` ADD FULLTEXT KEY `idx_busca` (`nome`,`descricao`,`marca`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
