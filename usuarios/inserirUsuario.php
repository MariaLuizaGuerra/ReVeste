<?php
    require_once __DIR__ . '/../src/conexao-bd.php';
    require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';
    require_once __DIR__ . '/../src/Modelo/Usuario.php';

    $nome = 'Ana';
    $perfil = 'admin';
    $email = 'anavitoria@exemplo.com';
    $senha = 'anabel';
  

    $repo = new UsuarioRepositorio($pdo);
    //Verificar se o usuario existe
    if($repo->buscarPorEmail($email))
    {
        echo "Usuario já existe! {$email}\n";
        exit;
    }

    $repo->salvar(new Usuario(0, $nome, $perfil, $email, $senha));
    

    echo "Usuário inserido: {$email}\n";



?>