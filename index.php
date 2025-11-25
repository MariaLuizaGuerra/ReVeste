<?php

require "src/conexao-bd.php";
require "src/Modelo/Produto.php";
require "src/Repositorio/ProdutoRepositorio.php";

$produtosRepositorio = new ProdutoRepositorio($pdo);
$dadosRoupas = $produtosRepositorio->opcoesRoupas();
$dadosAcessorios = $produtosRepositorio->opcoesAcessorios();



?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="img/Logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>ReVeste</title>
</head>
<body>
    <main>

    
</head>
<body>

<div class="container">

    <!-- TOPO -->
    <header class="topo">
        <div class="icons">
            <span class="icon">🔔</span>
            <span class="icon">➕</span>
        </div>

        <h1 class="logo">ReVESTE</h1>

        <div class="login-buttons">
            <button class="btn-login">LOGIN</button>
            <button class="btn-singup">SING UP</button>
        </div>
    </header>

    <!-- BANNER -->
    <section class="banner">
        <img src="market.jpg" class="banner-img">

        <div class="texto">
            <div class="linha"></div>
            <h2>


    <section class="container-banner">
            <div class="container-texto-banner">
                <img src="img/fundoLogin.jpg" class="logo" alt="logo-granato">
            </div>

    
    <section class="categories">
        <div class="category">
            <img src="img/feminino.jpg" alt="Feminino">
            <p>Feminino</p>
        </div>
        <div class="category">
            <img src="img/masculino.jpg" alt="Masculino">
            <p>Masculino</p>
        </div>
        <div class="category">
            <img src="img/infantil.jpg" alt="Infantil">
            <p>Infantil</p>
        </div>
        <div class="category">
            <img src="img/pulssize.jpg" alt="Plus Size">
            <p>Plus Size</p>
        </div>
    </section>

    <section class="suggestions">
        <h2>Para você:</h2>
            <div class="container-cafe-manha-produtos">
                <?php foreach ($dadosCafe as $cafe): ?>
                    <div class="container-produto">
                        <div class="container-foto">
                            <img src="<?= $cafe->getImagemDiretorio() ?>">
                        </div>
                        <p><?= $cafe->getNome() ?></p>
                        <p><?= $cafe->getDescricao() ?></p>
                        <p><?= $cafe->getPrecoFormatado() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
            <div class="containerprodutos">
                <?php foreach ($dadosAlmoco as $almoco): ?>
                    <div class="container-produto">
                        <div class="container-foto">
                            <img src="<?= $almoco->getImagemDiretorio() ?>">
                        </div>
                        <p><?= $almoco->getNome() ?></p>
                        <p><?= $almoco->getDescricao() ?></p>
                        <p><?= $almoco->getPrecoFormatado() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </section>
</main>
</body>
</html>
