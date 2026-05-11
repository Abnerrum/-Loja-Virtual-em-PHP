<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/functions.php';

$id   = (int)($_GET['id']   ?? 0);
$acao = $_GET['acao'] ?? '';

if ($id > 0 && isset($_SESSION['carrinho'][$id])) {
    if ($acao === 'mais') {
        $produto  = getProduto($pdo, $id);
        $qtdAtual = $_SESSION['carrinho'][$id];

        if ($produto && $qtdAtual < $produto['estoque']) {
            $_SESSION['carrinho'][$id]++;
        } else {
            flash('aviso', 'Quantidade máxima em estoque atingida.');
        }
    } elseif ($acao === 'menos') {
        $_SESSION['carrinho'][$id]--;
        if ($_SESSION['carrinho'][$id] <= 0) {
            unset($_SESSION['carrinho'][$id]);
            flash('info', 'Item removido do carrinho.');
        }
    }
}

header("Location: carrinho.php");
exit;