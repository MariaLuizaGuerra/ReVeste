 <?php
        require __DIR__ . "/../src/conexao-bd.php";
        require __DIR__ . "/../src/Modelo/Usuario.php";
        require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

        $usuarioRepositorio = new UsuarioRepositorio($pdo);

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $campos_obrigatorios = [
        'nome',
        'email',
        'senha',
        'data_nascimento',
        'sexo',
        'telefone',
        'endereco',
        'numero',
        'cidade',
        'estado',
        'perfil'
    ];
    foreach ($campos_obrigatorios as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {
            header("Location: cadastrar.php?erro=campos_vazios");
            exit();
        }
    }

    $email = $_POST['email'];
    if ($usuarioRepositorio->buscarPorEmail($email)) {
        header("Location: cadastrar.php?erro=email_existente");
        exit();
    }


    $usuario = new Usuario(
        null,
        $_POST['nome'],
        $_POST['perfil'],
        $_POST['email'],
        $_POST['senha'],
        $_POST['data_nascimento'],
        $_POST['sexo'],
        $_POST['telefone'],
        $_POST['endereco'],
        $_POST['numero'],
        $_POST['cidade'],
        $_POST['estado']
    );

    // 4 - Salvar no banco
    $usuarioRepositorio->salvar($usuario);

    // 5 - Redirecionar para login
    header("Location: login.php?sucesso=true");
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/cadastrar.css">
    
    <link rel="icon" href="img/reVeste_Logo.jpg" type="image/x-icon">
    <title>reVeste - Cadastro de Usuário</title>
</head>
<body>
    <main>
        <section class="container-cadastro">
            
            <h1 class="titulo-cadastro">Crie sua Conta reVeste</h1>

           
            <?php if ($erro === 'email_existente'): ?>
                <p class="mensagem-erro">Este e-mail já está cadastrado.</p>

            <?php elseif ($erro === 'campos_vazios'): ?>
                <p class="mensagem-erro">Por favor, preencha todos os campos obrigatórios.</p>

            <?php elseif ($sucesso === 'true'): ?>
                <p class="mensagem-sucesso">Cadastro realizado com sucesso! Faça login.</p>
            <?php endif; ?>

            <form action="autenticar.php" method="post" class="form-cadastro">
                
                <h2>Informações Pessoais</h2>
                
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Seu nome" required>

                <div class="form-row">
                    <div>
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" required>
                    </div>
                    <div>
                        <label for="telefone">Telefone (DDD + Número)</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="(99) 99999-9999" required>
                    </div>
                </div>

                <label for="sexo">Gênero</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione...</option>
                    <option value="F">Feminino</option>
                    <option value="M">Masculino</option>
                    <option value="O">Outro</option>
                    <option value="N">Prefiro não informar</option>
                </select>

                <label for="perfil">Perfil</label>
                <select id="perfil" name="perfil" required>
                    <option value="">Selecione...</option>
                    <option value="ADM">Administrador</option>
                    <option value="USR">Usuário</option>
                    <option value="O">Outro</option>
                </select>

                <br>
                <h2>Endereço</h2>

                <label for="endereco">Endereço (Rua/Avenida)</label>
                <input type="text" id="endereco" name="endereco" placeholder="Rua, Avenida, etc." required>
                
                <div class="form-row">
                    <div>
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero" placeholder="Ex: 123" required>
                    </div>
                    <div>
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" placeholder="Sua Cidade" required>
                    </div>
                </div>

                <label for="estado">Estado (UF)</label>
                <input type="text" id="estado" name="estado" maxlength="2" placeholder="Ex: SP" required>

                <br>
                <h2>Acesso</h2>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Seu e-mail" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>

                <!-- BOTÃO CORRIGIDO (SUBMIT) -->
                <button type="submit" class="botao-cadastrar">Criar conta</button>

                <a href="login.php" class="link-voltar">Já tem conta? Entrar</a>
            </form>

        </section>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', function(){
            var msgErro = document.querySelector('.mensagem-erro');
            var msgSucesso = document.querySelector('.mensagem-sucesso');
            
            function ocultar(msg) {
                 if(msg){
                    setTimeout(function(){
                        msg.style.display = 'none';
                    }, 4000);
                }
            }
            ocultar(msgErro);
            ocultar(msgSucesso);
        });
    </script>

</body>
</html>