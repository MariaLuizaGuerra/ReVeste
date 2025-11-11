<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

$categoriaRepositorio = new CategoriaRepositorio($pdo);

// --- LÓGICA DE PROCESSAMENTO DO FORMULÁRIO (POST) ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Se há um ID no POST, é uma atualização. Senão, é um cadastro.
    $id = !empty($_POST['id']) ? $_POST['id'] : null;
    $categoria = new Categoria(
        $id,
        $_POST['categoria'],
    );

    if ($categoria->getId()) {
        // Se tem ID, atualiza
        $categoriaRepositorio->atualizar($categoria);
    } else {
        // Se não tem ID, salva um novo
        $categoriaRepositorio->salvar($categoria);
    }

    header("Location: listar.php");
    exit(); // É uma boa prática usar exit() após um redirecionamento
}
