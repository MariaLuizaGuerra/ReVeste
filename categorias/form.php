<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Categoria.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';

$repo = new CategoriaRepositorio($pdo);

// Detecta se é edição
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$categoria = null;

if ($id) {
    // Ajuste o nome do método conforme o que existe no seu repositório (ex: buscarPorId / encontrar / buscar)
    if (method_exists($repo, 'buscar')) {
        $categoria = $repo->buscar($id);
    }

    if ($categoria) {
        $modoEdicao = true;
    } else {
        // id inválido -> voltar para lista
        header('Location: listar.php');
        exit;
    }
}

// Valores para o form
$valorCategoria       = $modoEdicao ? $categoria->getCategoria() : '';

$tituloPagina = $modoEdicao ? 'Editar Categoria' : 'Cadastrar Categoria';
$textoBotao   = $modoEdicao ? 'Salvar Alterações' : 'Cadastrar Categoria';
$actionForm   = $modoEdicao ? 'salvar.php' : 'salvar.php';
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?> - Granato</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
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
            <a href="../categorias/listar.php">Categorias</a>
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
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>

        <form action="<?= $actionForm ?>" method="post" class="form-produto">
            <?php if ($modoEdicao): ?>
                <input type="hidden" name="id" value="<?= (int)$categoria->getId() ?>">
            <?php endif; ?>

            <div>
                <label for="categoria">Categoria</label>
                <input id="categoria" name="categoria" type="text" required value="<?= htmlspecialchars($valorCategoria) ?>">
            </div>


            <div class="grupo-botoes">
                <button type="submit" class="botao-cadastrar"><?= htmlspecialchars($textoBotao) ?></button>
                <a href="listar.php" class="botao-voltar">Voltar</a>
            </div>
        </form>
    </main>
</body>

</html>