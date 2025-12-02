<?php
require "src/conexao-bd.php";
require "src/Modelo/Produto.php";
require "src/Repositorio/ProdutoRepositorio.php";

$repo = new ProdutoRepositorio($pdo);

$id = $_GET['id'];
$p = $repo->buscarPorId($id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <title><?= $p->getNome() ?></title>
</head>
<body>

<div style="margin:40px;">
    <img src="<?= $p->getImagemDiretorio() ?>" style="width:350px;border-radius:10px">

    <h1><?= $p->getNome() ?></h1>
    <p><?= $p->getDescricao() ?></p>
    <p class="preco"><?= $p->getPrecoFormatado() ?></p>

    <button class="btn-add">Adicionar ao carrinho</button>
</div>

</body>
</html>
