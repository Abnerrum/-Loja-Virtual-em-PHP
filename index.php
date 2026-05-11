<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/functions.php';

// Adicionar ao carrinho
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $produto = getProduto($pdo, $id);

    if ($produto && $produto['estoque'] > 0) {
        $qtdAtual = $_SESSION['carrinho'][$id] ?? 0;
        if ($qtdAtual < $produto['estoque']) {
            $_SESSION['carrinho'][$id] = $qtdAtual + 1;
            flash('sucesso', "\"{$produto['nome']}\" adicionado ao carrinho!");
        } else {
            flash('aviso', "Estoque insuficiente para \"{$produto['nome']}\".");
        }
    } else {
        flash('erro', 'Produto não encontrado ou sem estoque.');
    }
    header("Location: index.php");
    exit;
}

$produtos    = getProdutos($pdo);
$totalItens  = contarItens($_SESSION['carrinho'] ?? []);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loja Virtual</title>
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

    <h2 class="mb-4 fw-bold">Nossos Produtos</h2>

    <?php if (empty($produtos)): ?>
        <div class="alert alert-info">Nenhum produto disponível no momento.</div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($produtos as $p): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="<?= e($p['imagem_url'] ?? '') ?>"
                     class="card-img-top"
                     alt="<?= e($p['nome']) ?>"
                     style="height:200px;object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-semibold"><?= e($p['nome']) ?></h5>
                    <p class="card-text text-muted small flex-grow-1"><?= e($p['descricao'] ?? '') ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-success"><?= brl((float)$p['preco']) ?></span>
                        <?php if ($p['estoque'] > 0): ?>
                            <a href="index.php?add=<?= $p['id'] ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-cart-plus me-1"></i>Adicionar
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary">Sem estoque</span>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted mt-1">
                        <i class="bi bi-box-seam me-1"></i><?= $p['estoque'] ?> em estoque
                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<footer class="bg-dark text-secondary text-center py-3 mt-5">
    <small>&copy; <?= date('Y') ?> Loja Virtual. Todos os direitos reservados.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>