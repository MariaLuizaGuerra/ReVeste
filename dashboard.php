<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

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
     <link rel="icon" href="img/reVeste_Logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/admin.css">
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
    <nav class="menu-adm">
        <a href="dashboard.php">Dashboard</a>
        <a href="produtos/listar.php">Produtos</a>
         <?php if (pode('usuarios.listar')): ?>
        <a href="usuarios/listar.php">Usuários</a>
        <?php endif; ?>
    </nav>
    <div class="container-admin-banner">
        <a href="dashboard.php">
            <img src="img/reVeste_Logo.jpg" alt="Reveste" class="logo-admin">
        </a>
    </div>


</header>
    <main class="dashboard">
        <h1 class="titulo-dashboard">Dashboard</h1>
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
        </section>
    </main>
</body>

</html>