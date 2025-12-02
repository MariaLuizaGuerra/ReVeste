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
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";


$repo_categorias = new CategoriaRepositorio($pdo);
$listagemCategorias = $repo_categorias->buscarTodos();

$produtoRepositorio = new ProdutoRepositorio($pdo);
$offset = ($pagina_atual - 1) * $itens_por_pagina;

// Busca total de registros e produtos da página atual
$total_produtos = $produtoRepositorio->contarTotal();
$total_paginas = ceil($total_produtos / $itens_por_pagina);

// Parâmetros de ordenação
$ordem = filter_input(INPUT_GET, 'ordem') ?: null;
$direcao = filter_input(INPUT_GET, 'direcao') ?: 'ASC';

// Busca produtos com ordenação
$produtos = $produtoRepositorio->buscarPaginado($itens_por_pagina, $offset, $ordem, $direcao);

$itens_por_pagina = filter_input(INPUT_GET, 'itens_por_pagina', FILTER_VALIDATE_INT) ?: 5; // Valor padrão de 5


$produtoRepositorio = new ProdutoRepositorio($pdo);
$produtos = $produtoRepositorio->buscarTodos();

function gerarUrlOrdenacao($campo, $paginaAtual, $ordemAtual, $direcaoAtual, $itensPorPagina) {
    $novaDirecao = ($ordemAtual === $campo && $direcaoAtual === 'ASC') ? 'DESC' : 'ASC';
    return "?pagina={$paginaAtual}&ordem={$campo}&direcao={$novaDirecao}&itens_por_pagina={$itensPorPagina}";
}

// Mostrar ícone de ordenação
function mostrarIconeOrdenacao($campo, $ordemAtual, $direcaoAtual)
{
  if ($ordemAtual !== $campo) {
    // return '↕️';
    // &#8593; Seta direita - Unicode arrows
    // &#8595; Seta esquerda
    return '&#8597'; //Seta para cima e para baixo
  }
  return $direcaoAtual === 'ASC' ? '↑' : '↓';
}


function buscarNomeCategoria($id, $categorias)
{
  foreach ($categorias as $categoria) {
    if ($categoria->getId() == $id) {
      return $categoria->getCategoria();
    }
  }
  return 'Sem categoria';
}
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="icon" href="../img/logo.png" type="image/x-icon">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
    rel="stylesheet">
  <title>ReVeste - Produtos</title>
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
    

  </header>
  <main>

  <h2>Lista de Produtos</h2>
   <form class="form-paginacao" method="GET" action="">
    <label for="itens_por_pagina">Itens por página:</label>
    <select name="itens_por_pagina" id="itens_por_pagina" onchange="this.form.submit()">
        <option value="5" <?= $itens_por_pagina == 5 ? 'selected' : '' ?>>5</option>
        <option value="10" <?= $itens_por_pagina == 10 ? 'selected' : '' ?>>10</option>
        <option value="20" <?= $itens_por_pagina == 20 ? 'selected' : '' ?>>20</option>
    </select>
    <!-- Mantém os parâmetros de ordenação ao mudar o número de itens -->
    <input type="hidden" name="ordem" value="<?= htmlspecialchars($ordem) ?>">
    <input type="hidden" name="direcao" value="<?= htmlspecialchars($direcao) ?>">
</form>

    <h2>Lista de Produtos</h2>
    <button onclick="sortTable()">Ordenar Tabela</button>
    <section class="container-table">
      <table>
        <thead>
          <tr>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Tamanho</th>
            <th>Descricão</th>
            <th>
              <a href="<?= gerarUrlOrdenacao('descricao', $pagina_atual, $ordem, $direcao, $itens_por_pagina) ?>" style="color: inherit; text-decoration: none;">
                Descrição <?= mostrarIconeOrdenacao('descricao', $ordem, $direcao) ?>
              </a>
            </th>
            <th>Forma de pagamento Aceita</th>
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

      <div class="paginacao">
        <?php if ($total_paginas > 1): ?>
            <?php if ($pagina_atual > 1): ?>
                <a href="?pagina=<?= $pagina_atual - 1 ?>&ordem=<?= htmlspecialchars($ordem) ?>&direcao=<?= htmlspecialchars($direcao) ?>&itens_por_pagina=<?= $itens_por_pagina ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <?php if ($i == $pagina_atual): ?>
                    <strong><?= $i ?></strong>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>&ordem=<?= htmlspecialchars($ordem) ?>&direcao=<?= htmlspecialchars($direcao) ?>&itens_por_pagina=<?= $itens_por_pagina ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($pagina_atual < $total_paginas): ?>
                <a href="?pagina=<?= $pagina_atual + 1 ?>&ordem=<?= htmlspecialchars($ordem) ?>&direcao=<?= htmlspecialchars($direcao) ?>&itens_por_pagina=<?= $itens_por_pagina ?>">Próximo</a>
            <?php endif; ?>
        <?php endif; ?>
      </div>
      
      <a class="botao-cadastrar" href="form.php">Cadastrar produto</a>
      <form action="gerador-pdf.php" method="post" style="display:inline;">
        <input type="submit" class="botao-cadastrar" value="Baixar Relatório">
      </form>
    </section>
   
  </main>
</body>

</html>