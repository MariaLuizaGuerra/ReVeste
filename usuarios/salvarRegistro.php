<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$usuarioRepositorio = new UsuarioRepositorio($pdo);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$nome    = trim($_POST['nome'] ?? '');
$perfil  = 'User';
$email   = trim($_POST['email'] ?? '');
$senha   = $_POST['senha'] ?? '';

if ($nome === '' || $email === '' || $senha === '') {
    header("Location: registrar.php?erro=campos");
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO usuarios (nome, perfil, email, senha) VALUES (?, ?, ?, ?)');
$stmt->execute([$nome, $perfil, $email, password_hash($senha, PASSWORD_DEFAULT)]);

header("Location: ../login.php?novo=1");
