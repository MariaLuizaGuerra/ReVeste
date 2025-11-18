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


    <title>reVeste - Login</title>
</head>


<body>
    <main>
        <?php if ($usuarioLogado): ?>
            <section class="container-topo">
                <div class="topo-direita">
                    <p>Você já está logado como <strong><?= htmlspecialchars($usuarioLogado) ?></strong></p>
                    <form action="logout.php" method="post">
                        <button type="submit" class="botao-sair">Sair</button>
                    </form>
                </div>
                <div class="conteudo">
                    <a href="admin.php" class="link-adm">Ir para o painel do brechó</a>
                </div>
            </section>


        <?php else: ?>


            <section class="container-login">
           
                <h1 class="titulo-login">Login reVeste</h1>


                <?php if ($erro === 'credenciais'): ?>
                    <p class="mensagem-erro">Usuário ou senha incorretos.</p>
                <?php elseif ($erro === 'campos'): ?>
                    <p class="mensagem-erro">Preencha e-mail e senha.</p>
                <?php endif; ?>


                <form action="autenticar.php" method="post" class="form-login">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite o seu e-mail" required>


                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite a sua senha" required>


                    <input type="submit" class="botao-entrar" value="Entrar">
                    <input type="submit" class="botao-cadastrar" value="Cadastrar">


                </form>
            </section>


        <?php endif; ?>
    </main>


    <script>
        window.addEventListener('DOMContentLoaded', function(){
            var msg = document.querySelector('.mensagem-erro');
            if(msg){
                setTimeout(function(){
                    msg.classList.add('oculto');
                }, 4000);
            }
        });
    </script>
</body>


</html>

