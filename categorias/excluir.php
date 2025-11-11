<?php

require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

$categoriaRepositorio = new CategoriaRepositorio($pdo);
$categoriaRepositorio->deletar($_POST['id']);

header("Location: listar.php");
