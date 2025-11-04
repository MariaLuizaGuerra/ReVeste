<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarios = $usuarioRepositorio->buscarTodos();
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" href="../img/reVeste_Logo.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <title>ReVeste - Usuários</title>
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
                <img src="../img/reVeste_Logo" alt="ReVeste" class="logo-admin">
            </a>
        </div>


    </header>
    <main>
        <h2>Lista de Usuários</h2>
        <section class="container-table">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Perfil</th>
                        <th>Email</th>
                        <th colspan="2">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario->getNome()) ?></td>
                            <td><?= htmlspecialchars($usuario->getPerfil()) ?></td>
                            <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                            <td><a class="botao-editar" href="form.php?id=<?= $usuario->getId() ?>">Editar</a></td>
                            <td>
                                <form action="excluir.php" method="post">
                                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                    <input type="submit" class="botao-excluir" value="Excluir">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="botao-cadastrar" href="form.php">Cadastrar usuário</a>
        </section>
    </main>
</body>

</html>