<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'];

// Função de permissão
function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - ReVeste</title>
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body class="pagina-dashboard">

<header class="container-admin">
    <div class="topo-direita">
        <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
        <form action="logout.php" method="post" style="display:inline;">
            <button type="submit" class="botao-sair">Sair</button>
        </form>
    </div>
</header>

<main class="dashboard">
    <h1 class="titulo-dashboard">O que deseja ver?</h1>

    <section class="cards-container">

    <?php if (pode('usuarios.listar')): ?>
        <a class="card card-usuarios" href="usuarios/listar.php">
            <h2>Usuários</h2>
            <p>Gerenciar e cadastrar usuários.</p>
        </a>
    <?php endif; ?>

    <?php if (pode('produtos.listar')): ?>
        <a class="card card-produtos" href="produtos/listar.php">
            <h2>Produtos</h2>
            <p>Listar e gerenciar produtos.</p>
        </a>
    <?php endif; ?>

    <?php if (pode('categorias.listar')): ?>
        <a class="card card-categorias" href="categorias/listar.php">
            <h2>Categorias</h2>
            <p>Ver lista de categorias.</p>
        </a>
    <?php endif; ?>

    <?php if (pode('carrinho.listar')): ?>
        <a class="card card-carrinho" href="carrinho/listar.php">
            <h2>Carrinho</h2>
            <p>Ver itens do carrinho.</p>
        </a>
    <?php endif; ?>

    <?php if (pode('pedidos.listar')): ?>
        <a class="card card-pedidos" href="pedidos/listar.php">
            <h2>Pedidos</h2>
            <p>Listar pedidos realizados.</p>
        </a>
    <?php endif; ?>

</section>


</main>

</body>
</html>


