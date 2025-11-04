<?php

require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Produto.php";
require __DIR__ . "/../src/Repositorio/ProdutoRepositorio.php";

$produtoRepositorio = new ProdutoRepositorio($pdo);
$produtoRepositorio->deletar($_POST['id']);

header("Location: listar.php");
