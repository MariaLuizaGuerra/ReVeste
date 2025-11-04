<?php

session_start();

require_once __DIR__ . '/src/conexao-bd.php';
require_once __DIR__ . '/src/Modelo/Usuario.php';
require_once __DIR__ . '/src/Repositorio/UsuarioRepositorio.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

//Campos obrigatórios

if ($email === '' || $senha === '') {
    header('Location: login.php?erro=campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);
$usuario = $repo->buscarPorEmail($email);

if ($repo->autenticar($email, $senha)) {
    session_regenerate_id(true); // previne fixation
    // Mapeia permissões conforme perfil
    $perfil = $usuario->getPerfil();
    $_SESSION['usuario'] = $email; // mantém compatibilidade com login.php
    $_SESSION['permissoes'] = $perfil === 'Admin' ? ['usuarios.listar',  'produtos.listar'] : ['produtos.listar'];
    header('Location: dashboard.php'); // alterado de admin.php
    exit;
}

header('Location: login.php?erro=credenciais');
exit;
