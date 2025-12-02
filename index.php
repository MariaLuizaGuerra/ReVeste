<?php
require "src/conexao-bd.php";
require "src/Modelo/Produto.php";
require "src/Repositorio/ProdutoRepositorio.php";


$repo = new ProdutoRepositorio($pdo);

$dadosRoupas = $repo->opcoesRoupas();
$dadosAcessorios = $repo->opcoesAcessorios();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="img/Logo.png" type="image/x-icon">

    <title>ReVeste</title>
</head>

<body>

<header class="topo">
    <div class="icons">
        <span class="icon">🔔</span>
        <span class="icon">🛒</span>
    </div>

    <h1 class="logo">ReVESTE</h1>

    <div class="login-buttons">
        <a href="login.php" class="btn-login">LOGIN</a>
        <a href="signup.php" class="btn-signup">SIGN UP</a>
    </div>
</header>

<main>
   
    <section class="categories">
        <div class="category"><img src="img/feminino.jpg" alt=""><p>Feminino</p></div>
        <div class="category"><img src="img/masculino.jpg" alt=""><p>Masculino</p></div>
        <div class="category"><img src="img/infantil.jpg" alt=""><p>Infantil</p></div>
        <div class="category"><img src="img/pulssize.jpg" alt=""><p>Plus Size</p></div>
    </section>

   
    <section class="suggestions">
        <h2>Roupas</h2>

        <div class="container-produtos">

            <?php foreach ($dadosRoupas as $p): ?>
                <a href="produto.php?id=<?= $p->getId() ?>" class="produto">
                    <div class="foto-produto">
                        <img src="<?= $p->getImagemDiretorio() ?>" alt="">
                    </div>

                    <p class="nome"><?= $p->getNome() ?></p>
                    <p class="descricao"><?= $p->getDescricao() ?></p>
                    <p class="preco"><?= $p->getPrecoFormatado() ?></p>

                    <button class="btn-add">Adicionar ao carrinho</button>
                </a>
            <?php endforeach; ?>

        </div>
    </section>

    <!-- LISTA DE ACESSÓRIOS -->
    <section class="suggestions">
        <h2>Acessórios</h2>

        <div class="container-produtos">

            <?php foreach ($dadosAcessorios as $p): ?>
                <a href="produto.php?id=<?= $p->getId() ?>" class="produto">
                    <div class="foto-produto">
                        <img src="<?= $p->getImagemDiretorio() ?>" alt="">
                    </div>

                    <p class="nome"><?= $p->getNome() ?></p>
                    <p class="descricao"><?= $p->getDescricao() ?></p>
                    <p class="preco"><?= $p->getPrecoFormatado() ?></p>

                    <button class="btn-add">Adicionar ao carrinho</button>
                </a>
            <?php endforeach; ?>

        </div>
    </section>

        <title>ReVeste - Closet</title>
</head>

<body>
    <main>


</main>

</body>
</html>
