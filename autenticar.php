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

if ($email === '' || $senha === '') {
    header('Location: login.php?erro=campos');
    exit;
}

$repo = new UsuarioRepositorio($pdo);
$usuario = $repo->buscarPorEmail($email);

// Verifica se o usuário existe
if (!$usuario) {
    header('Location: login.php?erro=credenciais');
    exit;
}

// Verifica a senha usando password_verify
if (!password_verify($senha, $usuario->getSenha())) {
    header('Location: login.php?erro=credenciais');
    exit;
}

// Login OK
session_regenerate_id(true);

$_SESSION['usuario'] = $email;
$perfil = $usuario->getPerfil();

if ($perfil === 'Admin') {
    $_SESSION['permissoes'] = [
        'usuarios.listar',
        'produtos.listar',
        'categorias.listar',
        'pedidos.listar'
    ];
} else { // perfil User
    $_SESSION['permissoes'] = [
        'produtos.listar',
        'categorias.listar',
        'carrinho.listar'
    ];
}


header('Location: dashboard.php');
exit;
