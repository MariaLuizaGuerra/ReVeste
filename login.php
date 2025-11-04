<?php
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;
$erro = $_GET['erro'] ?? '';

?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="img/reVeste_Logo.jpg" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>ReVeste - Login</title>
</head>

<body>
    <main>
        <?php
        if ($usuarioLogado): ?>
            <section class="container-topo">
                <div class="topo-direita">
                    <p>Você já está logado como <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong></p>
                    <form action="logout.php" method="post">
                        <button type="submit" class="botao-sair">Sair</button>
                    </form>
                </div>
                <div class="conteudo">
                    <a href="dashboard.php" class="link-adm">Ir para o Dashboard</a>
                </div>
            </section>

        <?php else: ?>

            <section class="container-admin-banner">
                <img src="img/reVeste_Logo.jpg" class="logo-admin" alt="logo-reVeste">
                <h1>Login reVeste</h1>
                <img class="ornaments" src="img/ornaments-coffee.png" alt="ornaments">
            </section>
            <section class="container-form">
                <div class="form-wrapper">
                    <?php if ($erro === 'credenciais'): ?>
                        <p class="mensagem-erro">Usuário ou senha incorretos.</p>
                    <?php elseif ($erro === 'campos'): ?>
                        <p class="mensagem-erro">Preencha e-mail e senha.</p>
                    <?php endif; ?>
                    <?php if (isset($_GET['novo']) && $_GET['novo'] == 1): ?>
                        <p class="mensagem-ok">Usuário registrado. Faça login.</p>
                    <?php endif; ?>
                    <form action="autenticar.php" method="post">

                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Digite o seu e-mail">

                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite a sua senha">

                        <div class="acoes-login">
                            <button type="submit" class="botao-primario">Entrar</button>
                            <a href="usuarios/registrar.php" class="botao-secundario">Cadastrar</a>
                        </div>

                    </form>
                </div>
            </section>

        <?php
        endif;
        ?>
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