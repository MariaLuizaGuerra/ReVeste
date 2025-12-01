<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../src/conexao-bd.php'; // ajuste se necessário

// permissões (opcional)
function pode(string $perm): bool {
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}

// Tratamento de POST para adicionar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome !== '') {
        $st = $pdo->prepare("INSERT INTO categorias (nome, descricao, criado_em) VALUES (?, ?, NOW())");
        $st->execute([$nome, $_POST['descricao'] ?? '']);
        header('Location: listar.php');
        exit;
    }
}

// Remover categoria via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $st = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        $st->execute([$id]);
        header('Location: listar.php');
        exit;
    }
}

// Pega todas as categorias
$st = $pdo->query("SELECT id, nome, descricao, criado_em FROM categorias ORDER BY nome");
$categorias = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Categorias - ReVeste</title>
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .lista { max-width:1000px; margin:28px auto; padding:12px; }
    .cabecalho { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .form-add { display:flex; gap:8px; align-items:center; }
    .form-add input, .form-add textarea { padding:8px; border-radius:6px; border:1px solid #ddd; }
    table { width:100%; border-collapse:collapse; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,0.04); border-radius:8px; overflow:hidden; }
    th, td { padding:12px 14px; text-align:left; border-bottom:1px solid #f1f1f1; }
    th { background:#fafafa; font-weight:600; }
    .acoes form{ display:inline; margin-right:6px; }
    .btn { padding:8px 12px; border-radius:6px; border:none; cursor:pointer; }
    .btn-primary { background:#27ae60; color:#fff; }
    .btn-danger { background:#e74c3c; color:#fff; }
  </style>
</head>
<body>
  <header class="container-admin">
    <div class="topo-direita">
      <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
      <form action="../logout.php" method="post" style="display:inline;">
          <button type="submit" class="botao-sair">Sair</button>
      </form>
    </div>
  </header>

  <main class="lista">
    <div class="cabecalho">
      <h1>Categorias</h1>
      <form class="form-add" method="post" action="listar.php">
        <input type="hidden" name="acao" value="adicionar">
        <input name="nome" placeholder="Nova categoria" required>
        <button class="btn btn-primary" type="submit">Adicionar</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Criado em</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($categorias) === 0): ?>
          <tr><td colspan="4">Nenhuma categoria encontrada.</td></tr>
        <?php else: ?>
          <?php foreach ($categorias as $c): ?>
            <tr>
              <td><?php echo htmlspecialchars($c['nome']); ?></td>
              <td><?php echo htmlspecialchars($c['descricao']); ?></td>
              <td><?php echo htmlspecialchars($c['criado_em']); ?></td>
              <td class="acoes">
                <!-- Editar poderia ser uma página separada; aqui só link -->
                <a class="btn" href="editar.php?id=<?php echo (int)$c['id']; ?>">Editar</a>

                <!-- Deletar via POST por segurança -->
                <form method="post" action="listar.php" onsubmit="return confirm('Confirma exclusão?');" style="display:inline;">
                  <input type="hidden" name="acao" value="deletar">
                  <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                  <button type="submit" class="btn btn-danger">Deletar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
