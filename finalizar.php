<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/functions.php';

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

// Valida CSRF
validarCsrf();

// Carrinho vazio?
if (empty($_SESSION['carrinho'])) {
    flash('aviso', 'Seu carrinho está vazio.');
    header("Location: index.php");
    exit;
}

// Sanitiza entradas
$nome      = trim(htmlspecialchars($_POST['nome']      ?? '', ENT_QUOTES, 'UTF-8'));
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$telefone  = trim(htmlspecialchars($_POST['telefone']  ?? '', ENT_QUOTES, 'UTF-8'));
$pagamento = trim(htmlspecialchars($_POST['pagamento'] ?? '', ENT_QUOTES, 'UTF-8'));

// Validação básica
$erros = [];
if (strlen($nome) < 3)              $erros[] = 'Informe seu nome completo.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
if (!in_array($pagamento, ['pix', 'cartao_credito', 'boleto'])) $erros[] = 'Selecione uma forma de pagamento.';

if (!empty($erros)) {
    flash('erro', implode(' | ', $erros));
    header("Location: checkout.php");
    exit;
}

// Salva pedido no banco
$carrinho = $_SESSION['carrinho'];
$total    = calcularTotal($pdo, $carrinho);

try {
    $pedidoId = salvarPedido($pdo, $nome, $email, $telefone, $total, $carrinho);
} catch (Exception $e) {
    error_log($e->getMessage());
    flash('erro', 'Ocorreu um erro ao processar seu pedido. Tente novamente.');
    header("Location: checkout.php");
    exit;
}

// Limpa carrinho
unset($_SESSION['carrinho']);

// Busca itens para exibir na página
$stmt = $pdo->prepare(
    "SELECT ip.*, p.imagem_url
       FROM itens_pedido ip
       LEFT JOIN produtos p ON p.id = ip.produto_id
      WHERE ip.pedido_id = ?"
);
$stmt->execute([$pedidoId]);
$itens = $stmt->fetchAll();

$labelPagamento = ['pix' => 'PIX', 'cartao_credito' => 'Cartão de crédito', 'boleto' => 'Boleto bancário'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedido Confirmado — Loja Virtual</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            <i class="bi bi-shop me-2"></i>Loja Virtual
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Header de sucesso -->
            <div class="text-center mb-5">
                <div class="display-1 text-success mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="fw-bold">Pedido Confirmado!</h2>
                <p class="text-muted">
                    Obrigado, <strong><?= e($nome) ?></strong>!
                    Seu pedido <strong>#<?= $pedidoId ?></strong> foi recebido com sucesso.
                </p>
                <p class="text-muted small">
                    Uma confirmação foi registrada para <strong><?= e($email) ?></strong>.
                </p>
            </div>

            <!-- Detalhes do pedido -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Itens do Pedido</h5>
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Qtd.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= e($item['nome_produto']) ?></td>
                            <td class="text-center"><?= $item['quantidade'] ?></td>
                            <td class="text-end"><?= brl((float)$item['preco_unitario'] * $item['quantidade']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="fw-bold text-end">Total pago:</td>
                                <td class="fw-bold text-success text-end"><?= brl($total) ?></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="d-flex gap-2 mt-2 small text-muted">
                        <span><i class="bi bi-credit-card me-1"></i><?= e($labelPagamento[$pagamento] ?? $pagamento) ?></span>
                        <span class="ms-auto"><i class="bi bi-calendar me-1"></i><?= date('d/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="index.php" class="btn btn-success btn-lg fw-bold">
                    <i class="bi bi-arrow-left me-2"></i>Continuar comprando
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>