<?php
// ==============================================
//  functions.php — Funções auxiliares
// ==============================================

/* ---------- Produto ---------- */

function getProduto(PDO $pdo, int $id): array|false
{
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ? AND ativo = 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getProdutos(PDO $pdo): array
{
    return $pdo->query("SELECT * FROM produtos WHERE ativo = 1 ORDER BY nome")->fetchAll();
}

/* ---------- Carrinho ---------- */

function calcularTotal(PDO $pdo, array $carrinho): float
{
    $total = 0.0;
    foreach ($carrinho as $id => $qtd) {
        $produto = getProduto($pdo, (int)$id);
        if ($produto) {
            $total += $produto['preco'] * $qtd;
        }
    }
    return $total;
}

function contarItens(array $carrinho): int
{
    return array_sum($carrinho);
}

/* ---------- Pedido ---------- */

function salvarPedido(PDO $pdo, string $nome, string $email, string $telefone, float $total, array $carrinho): int
{
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO pedidos (nome, email, telefone, total) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$nome, $email, $telefone, $total]);
    $pedidoId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare(
        "INSERT INTO itens_pedido (pedido_id, produto_id, nome_produto, preco_unitario, quantidade)
         VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($carrinho as $id => $qtd) {
        $produto = getProduto($pdo, (int)$id);
        if ($produto) {
            $stmtItem->execute([$pedidoId, $id, $produto['nome'], $produto['preco'], $qtd]);

            // Decrementa estoque
            $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?")
                ->execute([$qtd, $id]);
        }
    }

    $pdo->commit();
    return $pedidoId;
}

/* ---------- CSRF ---------- */

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
}

function validarCsrf(): void
{
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('<div class="alert alert-danger text-center m-5">Requisição inválida. Por favor, tente novamente.</div>');
    }
}

/* ---------- Flash messages ---------- */

function flash(string $tipo, string $mensagem): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function mostrarFlash(): string
{
    if (!empty($_SESSION['flash'])) {
        $f   = $_SESSION['flash'];
        $map = ['sucesso' => 'success', 'erro' => 'danger', 'aviso' => 'warning', 'info' => 'info'];
        $cls = $map[$f['tipo']] ?? 'secondary';
        unset($_SESSION['flash']);
        return '<div class="alert alert-' . $cls . ' alert-dismissible fade show" role="alert">'
             . htmlspecialchars($f['mensagem'])
             . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
    return '';
}

/* ---------- Formatação ---------- */

function brl(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}