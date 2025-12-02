<?php
session_start();
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);
$usuarios = $repo->buscarTodos();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários - ReVeste</title>
    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<header class="container-admin">


    <div class="topo-direita">
        <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        <form action="../logout.php" method="post" style="display:inline;">
            <button type="submit" class="botao-sair">Sair</button>
        </form>
        
    </div>

     <nav class="menu">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../produtos/listar.php">Produtos</a>
        <a href="../usuarios/listar.php">Usuários</a>
    </nav>
</header>

<main>
    <h1 class="titulo">Lista de Usuários</h1>

    <table class="tabela-usuarios">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Perfil</th>
                <th>Email</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u->getNome()) ?></td>
                <td><?= htmlspecialchars($u->getPerfil()) ?></td>
                <td><?= htmlspecialchars($u->getEmail()) ?></td>
                <td>
                    <a class="botao editar" href="editar.php?id=<?= $u->getId() ?>">Editar</a>
                    <a class="botao excluir" href="excluir.php?id=<?= $u->getId() ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <a class="botao-cadastrar" href="registrar.php">Cadastrar usuário</a>
</main>

</body>
</html>
