<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/functions.php';

// Redirecionar se carrinho vazio
if (empty($_SESSION['carrinho'])) {
    flash('aviso', 'Seu carrinho está vazio.');
    header("Location: index.php");
    exit;
}

$carrinho   = $_SESSION['carrinho'];
$totalItens = contarItens($carrinho);
$total      = calcularTotal($pdo, $carrinho);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout — Loja Virtual</title>
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
        <a href="carrinho.php" class="btn btn-outline-light position-relative">
            <i class="bi bi-cart3 me-1"></i>Carrinho
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $totalItens ?>
            </span>
        </a>
    </div>
</nav>

<div class="container py-5">

    <?= mostrarFlash() ?>

    <h2 class="mb-4 fw-bold"><i class="bi bi-credit-card me-2"></i>Finalizar Compra</h2>

    <div class="row g-4">

        <!-- Formulário -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Dados do comprador</h5>

                    <form action="finalizar.php" method="POST" novalidate>
                        <?= csrfField() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome completo</label>
                            <input type="text" name="nome" class="form-control"
                                   placeholder="Seu nome completo" required
                                   value="<?= e($_POST['nome'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="seuemail@exemplo.com" required
                                   value="<?= e($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Telefone <span class="text-muted">(opcional)</span></label>
                            <input type="tel" name="telefone" class="form-control"
                                   placeholder="(11) 99999-9999"
                                   value="<?= e($_POST['telefone'] ?? '') ?>">
                        </div>

                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Pagamento</h5>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Forma de pagamento</label>
                            <select name="pagamento" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="pix">PIX</option>
                                <option value="cartao_credito">Cartão de crédito</option>
                                <option value="boleto">Boleto bancário</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg fw-bold mt-2">
                            <i class="bi bi-check-circle me-2"></i>Confirmar Pedido — <?= brl($total) ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumo -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Resumo</h5>
                    <?php foreach ($carrinho as $id => $qtd):
                        $p = getProduto($pdo, (int)$id);
                        if (!$p) continue;
                    ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><?= e($p['nome']) ?> <span class="text-muted">× <?= $qtd ?></span></span>
                        <span><?= brl((float)$p['preco'] * $qtd) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="text-success"><?= brl($total) ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>