<?php

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Usuario.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);

$tituloPagina = 'Registrar Usuário';
$textoBotao   = 'Registrar Usuário';
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?> - Granato</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <main>
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>
        <section class="container-form">
            <div class="form-wrapper">
                <?php if (isset($_GET['erro']) &&   $_GET['erro'] === 'campos'): ?>
                    <p class="mensagem-erro">Preencha e-mail e senha.</p>
                <?php endif; ?>
                <?php if (isset($_GET['novo']) && $_GET['novo'] == 1): ?>
                    <p class="mensagem-ok">Usuário registrado. Faça login.</p>
                <?php endif; ?>
                <form action="salvarRegistro.php" method="post" class="form-produto">
                    <input type="hidden" name="redirect" value="login">
                    <input type="hidden" name="perfil" value="User">

                    <div>
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" type="text">
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email">
                    </div>

                    <div>
                        <label for="senha">Senha</label>
                        <input id="senha" name="senha" type="password">
                    </div>

                    <div class="grupo-botoes">
                        <button type="submit" class="botao-cadastrar"><?= htmlspecialchars($textoBotao) ?></button>
                        <a href="login.php" class="botao-voltar">Voltar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <script>
        // Executa o código quando o documento estiver pronto
        window.addEventListener('DOMContentLoaded', () => {
            // Seleciona todas as mensagens
            const mensagens = document.querySelectorAll('.mensagem-erro, .mensagem-ok');

            mensagens.forEach(msg => {
                // 1. Define um timer para iniciar a animação de saída
                setTimeout(() => {
                    msg.classList.add('oculto');
                }, 5000); // 5 segundos

                // 2. Remove o elemento da página DEPOIS que a animação CSS terminar
                msg.addEventListener('transitionend', () => msg.remove());
            });
        });
    </script>
</body>

</html>