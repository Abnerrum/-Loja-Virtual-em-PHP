<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/functions.php';

$carrinho   = $_SESSION['carrinho'] ?? [];
$totalItens = contarItens($carrinho);
$total      = calcularTotal($pdo, $carrinho);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carrinho — Loja Virtual</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            <i class="bi bi-shop me-2"></i>Loja Virtual
        </a>
        <a href="carrinho.php" class="btn btn-outline-light position-relative">
            <i class="bi bi-cart3 me-1"></i>Carrinho
            <?php if ($totalItens > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $totalItens ?>
            </span>
            <?php endif; ?>
        </a>
    </div>
</nav>

<div class="container py-5">

    <?= mostrarFlash() ?>

    <h2 class="mb-4 fw-bold"><i class="bi bi-cart3 me-2"></i>Meu Carrinho</h2>

    <?php if (empty($carrinho)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <p class="mt-3 fs-5 text-muted">Seu carrinho está vazio.</p>
            <a href="index.php" class="btn btn-success mt-2">
                <i class="bi bi-arrow-left me-1"></i>Voltar à loja
            </a>
        </div>
    <?php else: ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Produto</th>
                                <th class="text-end">Preço Unit.</th>
                                <th class="text-center">Qtd.</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($carrinho as $id => $qtd):
                            $p = getProduto($pdo, (int)$id);
                            if (!$p) continue;
                        ?>
                        <tr>
                            <td class="fw-semibold"><?= e($p['nome']) ?></td>
                            <td class="text-end"><?= brl((float)$p['preco']) ?></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="atualizar.php?id=<?= $id ?>&acao=menos"
                                       class="btn btn-outline-secondary btn-sm px-2 py-0">
                                        <i class="bi bi-dash"></i>
                                    </a>
                                    <span class="fw-bold"><?= $qtd ?></span>
                                    <a href="atualizar.php?id=<?= $id ?>&acao=mais"
                                       class="btn btn-outline-secondary btn-sm px-2 py-0">
                                        <i class="bi bi-plus"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="text-end fw-semibold text-success">
                                <?= brl((float)$p['preco'] * $qtd) ?>
                            </td>
                            <td class="text-end">
                                <a href="remover.php?id=<?= $id ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Remover este item?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="index.php" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left me-1"></i>Continuar comprando
            </a>
        </div>

        <!-- Resumo -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Resumo do Pedido</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Itens (<?= $totalItens ?>)</span>
                        <span><?= brl($total) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frete</span>
                        <span class="text-success">Grátis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span class="text-success"><?= brl($total) ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-success w-100 fw-bold">
                        <i class="bi bi-credit-card me-2"></i>Finalizar Compra
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>