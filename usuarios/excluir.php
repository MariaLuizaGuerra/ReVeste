<?php

require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarioRepositorio->deletar($_POST['id']);

header("Location: listar.php");