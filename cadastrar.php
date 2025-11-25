<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="naoTemConta.css">
</head>
<body>
    <div class="box">
        <form action="">
        <fieldset>
            <legend><h1>Cadastre-se </h1></legend><br>
            <div class="input">
                <input type="text" name="nome" id="nome" class="inputUser" required>
                <label for="nome" class="label">Nome Completo</label>
            </div>
            <br>
            <div class="input">
                <input type="tel" name="Telefone" id="Telefone" class="inputUser" required>
                <label for="tel"class="label">Telefone</label>
            </div>
            <br>
            <div class="input">
                <input type="email" name="Email" id="email" class="inputUser" required>
                <label for="email"class="label">Email</label>
            </div>
            <br>
            <div class="input">
                <input type="password" name="Senha" id="senha" class="inputUser" required>
                <label for="senha"class="label">Senha</label>
            </div>
            <br>
            <p>Sexo: </p>
            <input type="radio" id="feminino" name="genero" value="feminino" required>
            <label for="feminino">Feminino</label>
            <br>
            <input type="radio" id="Masculino" name="Masculino" value="Masculino" required>
            <label for="Masculino">Masculino</label>
            <br>
            <input type="radio" id="Outro" name="Outro" value="Outro" required>
            <label for="Outro">Outro</label>
            <br>
            <br>
            <div class="input">
                <label for="date"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="inputUser" required>
            </div>
            <br>
            <div class="input">
                <input type="text" name="cidade" id="cidade" class="inputUser" required>
                <label for="cidade" class="label">cidade</label>
            </div>
            <br>
            <div class="input">
                <input type="text" name="ESTADO" id="estado" class="inputUser" required>
                <label for="estado" class="label">ESTADO</label>
            </div>
            <br>
            <div class="input">
                <input type="text" name="Endereco" id="endereço" class="inputUser" required>
                <label for="endereco"class="label">Endereço</label>
            </div>
            <br>
            <a href="login.html">Enviar</a>
        </fieldset>
    </form>
    </div>
</body>
</html>