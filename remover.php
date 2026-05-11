<?php
session_start();
include __DIR__ . '/functions.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0 && isset($_SESSION['carrinho'][$id])) {
    unset($_SESSION['carrinho'][$id]);
    flash('info', 'Item removido do carrinho.');
}

header("Location: carrinho.php");
exit;