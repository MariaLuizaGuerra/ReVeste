<?php
// filepath: c:\Users\Jair\Documents\projetosweb\projeto-final\produtos.php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
  header('Location: login.php');
  exit;
}
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Produto.php";
require __DIR__ . "/../src/Repositorio/ProdutoRepositorio.php";

$produtoRepositorio = new ProdutoRepositorio($pdo);
$produtos = $produtoRepositorio->buscarTodos();
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="icon" href="../img/icone-granato.png" type="image/x-icon">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
    rel="stylesheet">
  <title>Granato - Produtos</title>
</head>

<body>
  <header class="container-admin">
    <div class="topo-direita">
      <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
      <form action="../logout.php" method="post" style="display:inline;">
        <button type="submit" class="botao-sair">Sair</button>
      </form>
    </div>
    <nav class="menu-adm">
      <a href="../dashboard.php">Dashboard</a>
      <a href="../produtos/listar.php">Produtos</a>
      <a href="../usuarios/listar.php">Usuários</a>
    </nav>
    <div class="container-admin-banner">
      <a href="dashboard.php">
        <img src="../img/logo-granato-horizontal.png" alt="Granato" class="logo-admin">
      </a>
    </div>


  </header>
  <main>
    <h2>Lista de Produtos</h2>
    <section class="container-table">
      <table>
        <thead>
          <tr>
            <th>Produto</th>
            <th>Tamanho</th>
            <th>Tipo</th>
            <th>Descricão</th>
            <th>Forma de pagamento</th>
            <th>Valor</th>
            <th colspan="2">Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $produto): ?>
            <tr>
              <td><?= htmlspecialchars($produto->getNome()) ?></td>
              <td><?= htmlspecialchars($produto->getTipo()) ?></td>
              <td><?= htmlspecialchars($produto->getTamanho()) ?></td>
              <td><?= htmlspecialchars($produto->getDescricao()) ?></td>
              <td><?= htmlspecialchars($produto->getFormaPagamaento()) ?></td>
              <td><?= htmlspecialchars($produto->getPrecoFormatado()) ?></td>
              <td><a class="botao-editar" href="form.php?id=<?= $produto->getId() ?>">Editar</a></td>
              <td>
                <form action="excluir.php" method="post">
                  <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                  <input type="submit" class="botao-excluir" value="Excluir">
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <a class="botao-cadastrar" href="form.php">Cadastrar produto</a>
      <form action="gerador-pdf.php" method="post" style="display:inline;">
        <input type="submit" class="botao-cadastrar" value="Baixar Relatório">
      </form>
    </section>
  </main>
</body>

</html>