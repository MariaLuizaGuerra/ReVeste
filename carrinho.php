<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/src/conexao-bd.php'; // ajuste se necessário

// inicializa carrinho na sessão (formato: [produtoId => ['nome'=>, 'preco'=>, 'qtd'=>]])
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adicionar produto pelo GET (opcional utilidade)
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $id = (int)$_GET['add'];
    // busca produto no DB
    $st = $pdo->prepare("SELECT id, nome, preco FROM produtos WHERE id = ?");
    $st->execute([$id]);
    $p = $st->fetch(PDO::FETCH_ASSOC);
    if ($p) {
        if (!isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = ['nome' => $p['nome'], 'preco' => (float)$p['preco'], 'qtd' => 1];
        } else {
            $_SESSION['carrinho'][$id]['qtd']++;
        }
    }
    header('Location: carrinho.php');
    exit;
}

// Atualizar quantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] === 'atualizar') {
        foreach ($_POST['qtd'] as $id => $q) {
            $id = (int)$id;
            $q = (int)$q;
            if ($q <= 0) {
                unset($_SESSION['carrinho'][$id]);
            } else {
                if (isset($_SESSION['carrinho'][$id])) {
                    $_SESSION['carrinho'][$id]['qtd'] = $q;
                }
            }
        }
        header('Location: carrinho.php');
        exit;
    }
    if ($_POST['acao'] === 'limpar') {
        $_SESSION['carrinho'] = [];
        header('Location: carrinho.php');
        exit;
    }
    if ($_POST['acao'] === 'finalizar') {
        // Exemplo simples de salvar pedido: criar pedido e itens
        if (count($_SESSION['carrinho']) === 0) {
            header('Location: carrinho.php');
            exit;
        }
        $pdo->beginTransaction();
        try {
            $st = $pdo->prepare("INSERT INTO pedidos (usuario_email, total, criado_em) VALUES (?, ?, NOW())");
            $total = 0;
            foreach ($_SESSION['carrinho'] as $it) $total += $it['preco'] * $it['qtd'];
            $st->execute([$_SESSION['usuario'], $total]);
            $pedidoId = $pdo->lastInsertId();

            $stItem = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, nome, preco, quantidade) VALUES (?, ?, ?, ?, ?)");
            foreach ($_SESSION['carrinho'] as $prodId => $it) {
                $stItem->execute([$pedidoId, $prodId, $it['nome'], $it['preco'], $it['qtd']]);
            }

            $pdo->commit();
            $_SESSION['carrinho'] = [];
            $mensagem = "Pedido #{$pedidoId} criado com sucesso.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $mensagem = "Erro ao finalizar pedido: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Carrinho - ReVeste</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="css/carrinho.css">
  <link rel="icon" href="img/reVeste_Logo.jpg" type="image/x-icon">
    <title>reVeste - Categorias</title>
</head>
<body>
  <header class="container-admin">
    <div class="topo-direita">
      <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
      <form action="logout.php" method="post" style="display:inline;">
          <button type="submit" class="botao-sair">Sair</button>
      </form>
    </div>
  </header>

  <main class="wrap">
    <h1>Carrinho de Compras</h1>

    <?php if (!empty($mensagem)): ?>
      <div class="mensagem"><?php echo htmlspecialchars($mensagem); ?></div>
    <?php endif; ?>

    <form method="post" action="carrinho.php">
      <input type="hidden" name="acao" value="atualizar">
      <table>
        <thead>
          <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>SubTotal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($_SESSION['carrinho'])): ?>
            <tr><td colspan="4">Seu carrinho está vazio.</td></tr>
          <?php else: ?>
            <?php $total = 0; foreach ($_SESSION['carrinho'] as $id => $it): 
                $subtotal = $it['preco'] * $it['qtd'];
                $total += $subtotal;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($it['nome']); ?></td>
                <td>R$ <?php echo number_format($it['preco'], 2, ',', '.'); ?></td>
                <td>
                  <input class="qty" type="number" name="qtd[<?php echo (int)$id; ?>]" value="<?php echo (int)$it['qtd']; ?>" min="0">
                </td>
                <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="total">Total: <strong>R$ <?php echo number_format($total ?? 0, 2, ',', '.'); ?></strong></div>

      <div class="actions" style="margin-top:12px;">
        <button class="btn btn-primary" type="submit">Atualizar</button>
      </div>
    </form>

    <form method="post" action="carrinho.php" style="margin-top:8px;">
      <input type="hidden" name="acao" value="finalizar">
      <button class="btn btn-primary" type="submit" <?php echo empty($_SESSION['carrinho']) ? 'disabled' : ''; ?>>Finalizar Compra</button>
    </form>

    <form method="post" action="carrinho.php" style="margin-top:8px;">
      <input type="hidden" name="acao" value="limpar">
      <button class="btn btn-danger" type="submit" <?php echo empty($_SESSION['carrinho']) ? 'disabled' : ''; ?>>Limpar Carrinho</button>
    </form>
  </main>
</body>
</html>
