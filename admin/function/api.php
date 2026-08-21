<?php

session_start();

// Verificar se o usuário está logado
function checkAuth() {
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Não autorizado. Faça login primeiro.']);
        exit();
    }
    
    // Verificar se é admin (opcional)
    if ($_SESSION['usuario_tipo'] !== 'admin' && $_SESSION['usuario_tipo'] !== 'vendedor') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Acesso negado. Você não tem permissão para acessar esta área.']);
        exit();
    }
}

// Chamar a verificação para todas as requisições da API
// (exceto login que é público)
$public_actions = ['login', 'check_session'];
if (!in_array($_GET['action'] ?? '', $public_actions)) {
    checkAuth();
}
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Tratar requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'conexao.php';

class AdminAPI {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // ============================================================
    // DASHBOARD
    // ============================================================
    public function getDashboardStats() {
        try {
            // Total de produtos ativos
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM produtos WHERE status = 'ativo'");
            $totalProdutos = $stmt->fetch()['total'] ?? 0;
            
            // Pedidos hoje
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM pedidos WHERE DATE(data_pedido) = CURDATE()");
            $pedidosHoje = $stmt->fetch()['total'] ?? 0;
            
            // Faturamento mensal
            $stmt = $this->pdo->query("SELECT COALESCE(SUM(total), 0) as total FROM pedidos 
                                       WHERE MONTH(data_pedido) = MONTH(CURDATE()) 
                                       AND YEAR(data_pedido) = YEAR(CURDATE())
                                       AND status_pedido IN ('pago', 'processando', 'enviado', 'entregue')");
            $faturamento = (float)($stmt->fetch()['total'] ?? 0);
            
            // Clientes ativos
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE status = 'ativo' AND tipo_usuario = 'cliente'");
            $clientesAtivos = $stmt->fetch()['total'] ?? 0;
            
            // Produtos em falta (estoque <= estoque_minimo)
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM produtos WHERE estoque_atual <= estoque_minimo AND status = 'ativo'");
            $produtosFalta = $stmt->fetch()['total'] ?? 0;
            
            // Últimos pedidos
            $stmt = $this->pdo->query("SELECT p.*, u.nome_completo as cliente 
                                       FROM pedidos p 
                                       LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                                       ORDER BY p.data_pedido DESC LIMIT 5");
            $ultimosPedidos = $stmt->fetchAll();
            
            // Produtos mais vendidos
            $stmt = $this->pdo->query("SELECT pr.nome, c.nome as categoria, SUM(ip.quantidade) as vendas, 
                                       SUM(ip.subtotal) as receita
                                       FROM itens_pedido ip
                                       JOIN produtos pr ON ip.id_produto = pr.id_produto
                                       LEFT JOIN categorias c ON pr.categoria_id = c.id_categoria
                                       JOIN pedidos p ON ip.id_pedido = p.id_pedido
                                       WHERE p.status_pedido IN ('pago', 'processando', 'enviado', 'entregue')
                                       GROUP BY ip.id_produto
                                       ORDER BY vendas DESC LIMIT 3");
            $maisVendidos = $stmt->fetchAll();
            
            return [
                'success' => true,
                'data' => [
                    'total_produtos' => (int)$totalProdutos,
                    'pedidos_hoje' => (int)$pedidosHoje,
                    'faturamento_mensal' => $faturamento,
                    'clientes_ativos' => (int)$clientesAtivos,
                    'produtos_falta' => (int)$produtosFalta,
                    'ultimos_pedidos' => $ultimosPedidos,
                    'mais_vendidos' => $maisVendidos
                ]
            ];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // PRODUTOS
    // ============================================================
    public function getProdutos() {
        try {
            $stmt = $this->pdo->query("SELECT p.*, c.nome as categoria_nome 
                                       FROM produtos p 
                                       LEFT JOIN categorias c ON p.categoria_id = c.id_categoria 
                                       ORDER BY p.id_produto DESC");
            $produtos = $stmt->fetchAll();
            
            return ['success' => true, 'data' => $produtos];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getProduto($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
            $stmt->execute([$id]);
            $produto = $stmt->fetch();
            
            if (!$produto) {
                return ['success' => false, 'error' => 'Produto não encontrado'];
            }
            
            return ['success' => true, 'data' => $produto];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function createProduto($data) {
        try {
            // Validar campos obrigatórios
            if (empty($data['nome']) || empty($data['preco_venda'])) {
                return ['success' => false, 'error' => 'Nome e preço de venda são obrigatórios'];
            }
            
            $sql = "INSERT INTO produtos (
                nome, descricao, sku, categoria_id, marca, preco_venda, preco_custo,
                estoque_atual, estoque_minimo, status, destaque, novo, imagem_principal,
                preco_promocional, data_inicio_promocao, data_fim_promocao
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nome'],
                $data['descricao'] ?? null,
                $data['sku'] ?? null,
                !empty($data['categoria_id']) ? (int)$data['categoria_id'] : null,
                $data['marca'] ?? null,
                (float)$data['preco_venda'],
                isset($data['preco_custo']) ? (float)$data['preco_custo'] : null,
                isset($data['estoque_atual']) ? (int)$data['estoque_atual'] : 0,
                isset($data['estoque_minimo']) ? (int)$data['estoque_minimo'] : 5,
                $data['status'] ?? 'ativo',
                isset($data['destaque']) ? (int)$data['destaque'] : 0,
                isset($data['novo']) ? (int)$data['novo'] : 0,
                $data['imagem_principal'] ?? null,
                isset($data['preco_promocional']) ? (float)$data['preco_promocional'] : null,
                $data['data_inicio_promocao'] ?? null,
                $data['data_fim_promocao'] ?? null
            ]);
            
            return [
                'success' => true, 
                'message' => 'Produto criado com sucesso', 
                'id' => $this->pdo->lastInsertId()
            ];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function updateProduto($id, $data) {
        try {
            $fields = [];
            $values = [];
            
            $allowedFields = [
                'nome', 'descricao', 'sku', 'categoria_id', 'marca', 
                'preco_venda', 'preco_custo', 'estoque_atual', 'estoque_minimo', 
                'status', 'destaque', 'novo', 'imagem_principal', 
                'preco_promocional', 'data_inicio_promocao', 'data_fim_promocao'
            ];
            
            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'Nenhum campo para atualizar'];
            }
            
            $values[] = $id;
            $sql = "UPDATE produtos SET " . implode(', ', $fields) . " WHERE id_produto = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            
            return ['success' => true, 'message' => 'Produto atualizado com sucesso'];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function deleteProduto($id) {
        try {
            // Verificar se o produto existe
            $stmt = $this->pdo->prepare("SELECT id_produto FROM produtos WHERE id_produto = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                return ['success' => false, 'error' => 'Produto não encontrado'];
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id_produto = ?");
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Produto removido com sucesso'];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // PEDIDOS
    // ============================================================
    public function getPedidos($status = null) {
        try {
            $sql = "SELECT p.*, u.nome_completo as cliente 
                    FROM pedidos p 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario";
            
            if ($status) {
                $sql .= " WHERE p.status_pedido = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status]);
            } else {
                $stmt = $this->pdo->query($sql . " ORDER BY p.data_pedido DESC");
            }
            
            $pedidos = $stmt->fetchAll();
            
            // Buscar itens de cada pedido
            foreach ($pedidos as &$pedido) {
                $stmt = $this->pdo->prepare("SELECT ip.*, pr.nome as produto_nome 
                                            FROM itens_pedido ip 
                                            JOIN produtos pr ON ip.id_produto = pr.id_produto 
                                            WHERE ip.id_pedido = ?");
                $stmt->execute([$pedido['id_pedido']]);
                $pedido['itens'] = $stmt->fetchAll();
            }
            
            return ['success' => true, 'data' => $pedidos];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function updatePedidoStatus($id, $status) {
        try {
            $validStatus = ['pendente', 'pago', 'processando', 'enviado', 'entregue', 'cancelado'];
            if (!in_array($status, $validStatus)) {
                return ['success' => false, 'error' => 'Status inválido'];
            }
            
            $stmt = $this->pdo->prepare("UPDATE pedidos SET status_pedido = ? WHERE id_pedido = ?");
            $stmt->execute([$status, $id]);
            
            // Se status mudou para 'entregue', atualizar data_entrega
            if ($status === 'entregue') {
                $stmt = $this->pdo->prepare("UPDATE pedidos SET data_entrega = NOW() WHERE id_pedido = ?");
                $stmt->execute([$id]);
            }
            
            return ['success' => true, 'message' => 'Status do pedido atualizado'];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // CLIENTES
    // ============================================================
    public function getClientes() {
        try {
            $stmt = $this->pdo->query("SELECT id_usuario, nome_completo, email, cpf, telefone, status, data_cadastro 
                                       FROM usuarios 
                                       WHERE tipo_usuario = 'cliente' 
                                       ORDER BY nome_completo");
            return ['success' => true, 'data' => $stmt->fetchAll()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getCliente($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_usuario, nome_completo, email, cpf, telefone, status, 
                                                data_cadastro, data_nascimento, genero, perfil_img
                                         FROM usuarios 
                                         WHERE id_usuario = ? AND tipo_usuario = 'cliente'");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();
            
            if (!$cliente) {
                return ['success' => false, 'error' => 'Cliente não encontrado'];
            }
            
            // Buscar endereços do cliente
            $stmt = $this->pdo->prepare("SELECT * FROM enderecos WHERE id_usuario = ?");
            $stmt->execute([$id]);
            $cliente['enderecos'] = $stmt->fetchAll();
            
            return ['success' => true, 'data' => $cliente];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // CATEGORIAS
    // ============================================================
    public function getCategorias() {
        try {
            $stmt = $this->pdo->query("SELECT c.*, 
                                       (SELECT COUNT(*) FROM produtos WHERE categoria_id = c.id_categoria) as total_produtos
                                       FROM categorias c 
                                       ORDER BY c.nome");
            return ['success' => true, 'data' => $stmt->fetchAll()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function createCategoria($data) {
        try {
            if (empty($data['nome'])) {
                return ['success' => false, 'error' => 'Nome da categoria é obrigatório'];
            }
            
            $slug = !empty($data['slug']) ? $data['slug'] : 
                    strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $data['nome'])));
            
            $stmt = $this->pdo->prepare("INSERT INTO categorias (nome, descricao, slug, icone, status) 
                                         VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['nome'],
                $data['descricao'] ?? null,
                $slug,
                $data['icone'] ?? '📂',
                $data['status'] ?? 'ativo'
            ]);
            
            return ['success' => true, 'message' => 'Categoria criada com sucesso', 'id' => $this->pdo->lastInsertId()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function deleteCategoria($id) {
        try {
            // Verificar se existem produtos nesta categoria
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?");
            $stmt->execute([$id]);
            $total = $stmt->fetch()['total'] ?? 0;
            
            if ($total > 0) {
                return ['success' => false, 'error' => "Não é possível excluir: {$total} produto(s) estão usando esta categoria"];
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id_categoria = ?");
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Categoria removida com sucesso'];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // CUPONS
    // ============================================================
    public function getCupons() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM cupons ORDER BY data_criacao DESC");
            return ['success' => true, 'data' => $stmt->fetchAll()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function createCupom($data) {
        try {
            if (empty($data['codigo']) || empty($data['valor_desconto'])) {
                return ['success' => false, 'error' => 'Código e valor do desconto são obrigatórios'];
            }
            
            $sql = "INSERT INTO cupons (codigo, descricao, tipo_desconto, valor_desconto, valor_minimo_pedido, 
                    uso_total, data_inicio, data_fim, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                strtoupper($data['codigo']),
                $data['descricao'] ?? null,
                $data['tipo_desconto'] ?? 'percentual',
                (float)$data['valor_desconto'],
                isset($data['valor_minimo_pedido']) ? (float)$data['valor_minimo_pedido'] : 0,
                isset($data['uso_total']) ? (int)$data['uso_total'] : 1,
                $data['data_inicio'] ?? date('Y-m-d H:i:s'),
                $data['data_fim'] ?? date('Y-m-d H:i:s', strtotime('+30 days')),
                $data['status'] ?? 'ativo'
            ]);
            
            return ['success' => true, 'message' => 'Cupom criado com sucesso', 'id' => $this->pdo->lastInsertId()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function deleteCupom($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cupons WHERE id_cupom = ?");
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Cupom removido com sucesso'];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // LOGIN (Autenticação)
    // ============================================================
    public function login($email, $senha) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_usuario, nome_completo, email, senha_hash, tipo_usuario, status 
                                         FROM usuarios 
                                         WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            if (!$usuario) {
                return ['success' => false, 'error' => 'Email ou senha inválidos'];
            }
            
            if ($usuario['status'] !== 'ativo') {
                return ['success' => false, 'error' => 'Usuário inativo ou bloqueado'];
            }
            
            // Verificar senha (assumindo hash bcrypt)
            if (!password_verify($senha, $usuario['senha_hash'])) {
                return ['success' => false, 'error' => 'Email ou senha inválidos'];
            }
            
            // Atualizar último login
            $stmt = $this->pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id_usuario = ?");
            $stmt->execute([$usuario['id_usuario']]);
            
            unset($usuario['senha_hash']);
            
            return ['success' => true, 'data' => $usuario];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ============================================================
    // ESTATÍSTICAS ADICIONAIS
    // ============================================================
    public function getVendasPorPeriodo($periodo = 'mes') {
        try {
            $interval = $periodo === 'ano' ? 'YEAR(data_pedido)' : 'MONTH(data_pedido)';
            
            $stmt = $this->pdo->query("SELECT 
                                       DATE_FORMAT(data_pedido, '%Y-%m') as periodo,
                                       COUNT(*) as total_pedidos,
                                       SUM(total) as total_vendas
                                       FROM pedidos
                                       WHERE status_pedido IN ('pago', 'processando', 'enviado', 'entregue')
                                       GROUP BY DATE_FORMAT(data_pedido, '%Y-%m')
                                       ORDER BY periodo DESC
                                       LIMIT 12");
            
            return ['success' => true, 'data' => $stmt->fetchAll()];
        } catch(PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// ============================================================
// ROTEAMENTO DA API
// ============================================================
$api = new AdminAPI($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        case 'GET':
            switch ($action) {
                case 'dashboard':
                    echo json_encode($api->getDashboardStats());
                    break;
                case 'produtos':
                    echo json_encode($api->getProdutos());
                    break;
                case 'produto':
                    if (!$id) {
                        throw new Exception('ID do produto não informado');
                    }
                    echo json_encode($api->getProduto($id));
                    break;
                case 'pedidos':
                    $status = $_GET['status'] ?? null;
                    echo json_encode($api->getPedidos($status));
                    break;
                case 'clientes':
                    echo json_encode($api->getClientes());
                    break;
                case 'cliente':
                    if (!$id) {
                        throw new Exception('ID do cliente não informado');
                    }
                    echo json_encode($api->getCliente($id));
                    break;
                case 'categorias':
                    echo json_encode($api->getCategorias());
                    break;
                case 'cupons':
                    echo json_encode($api->getCupons());
                    break;
                case 'vendas':
                    $periodo = $_GET['periodo'] ?? 'mes';
                    echo json_encode($api->getVendasPorPeriodo($periodo));
                    break;
                default:
                    throw new Exception('Ação não encontrada');
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new Exception('Dados inválidos');
            }
            
            switch ($action) {
                case 'login':
                    $email = $data['email'] ?? '';
                    $senha = $data['senha'] ?? '';
                    echo json_encode($api->login($email, $senha));
                    break;
                case 'produto':
                    echo json_encode($api->createProduto($data));
                    break;
                case 'categoria':
                    echo json_encode($api->createCategoria($data));
                    break;
                case 'cupom':
                    echo json_encode($api->createCupom($data));
                    break;
                default:
                    throw new Exception('Ação não encontrada');
            }
            break;
            
        case 'PUT':
            if (!$id) {
                throw new Exception('ID não informado');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new Exception('Dados inválidos');
            }
            
            switch ($action) {
                case 'produto':
                    echo json_encode($api->updateProduto($id, $data));
                    break;
                case 'pedido':
                    $status = $data['status'] ?? null;
                    if (!$status) {
                        throw new Exception('Status não informado');
                    }
                    echo json_encode($api->updatePedidoStatus($id, $status));
                    break;
                default:
                    throw new Exception('Ação não encontrada');
            }
            break;
            
        case 'DELETE':
            if (!$id) {
                throw new Exception('ID não informado');
            }
            
            switch ($action) {
                case 'produto':
                    echo json_encode($api->deleteProduto($id));
                    break;
                case 'categoria':
                    echo json_encode($api->deleteCategoria($id));
                    break;
                case 'cupom':
                    echo json_encode($api->deleteCupom($id));
                    break;
                default:
                    throw new Exception('Ação não encontrada');
            }
            break;
            
        default:
            throw new Exception('Método não suportado');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>