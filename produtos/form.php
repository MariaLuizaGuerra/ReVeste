<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Produto.php';
require_once __DIR__ . '/../src/Repositorio/ProdutoRepositorio.php';

$repo = new ProdutoRepositorio($pdo);

// Detecta se é edição
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$produto = null;

if ($id) {
    // Ajuste o nome do método conforme o que existe no seu repositório (ex: buscarPorId / encontrar / buscar)
    if (method_exists($repo, 'buscar')) {
        $produto = $repo->buscar($id);
    }

    if ($produto) {
        $modoEdicao = true;
    } else {
        // id inválido -> voltar para lista
        header('Location: listar.php');
        exit;
    }
}

// Valores para o form
$valorNome       = $modoEdicao ? $produto->getNome() : '';
$valorTipo       = $modoEdicao ? $produto->getTipo() : '';
$valorDescricao  = $modoEdicao ? $produto->getDescricao() : '';
// Tentativa de obter preço "cru"
if ($modoEdicao) {
    if (method_exists($produto, 'getPreco')) {
        $valorPreco = $produto->getPreco(); // decimal puro
    } else {
        // Converte formato brasileiro para número (ex: 1.234,56 -> 1234.56)
        $formatado = $produto->getPrecoFormatado();
        $valorPreco = preg_replace('/\./', '', $formatado);
        $valorPreco = str_replace(',', '.', $valorPreco);
    }
} else {
    $valorPreco = '';
}

if ($modoEdicao) {
    if (method_exists($produto, 'getImagem')) {
        $valorImagem = $produto->getImagem();
    } else {
        $valorImagem = '';
    }
} else {
    $valorImagem = '';
}

$tituloPagina = $modoEdicao ? 'Editar Produto' : 'Cadastrar Produto';
$textoBotao   = $modoEdicao ? 'Salvar Alterações' : 'Cadastrar Produto';
$actionForm   = $modoEdicao ? 'salvar.php' : 'salvar.php';
?>

<?php
// Valores padrão para edição ou criação
$nome = $nome ?? '';
$tamanho = $tamanho ?? '';
$tipo = $tipo ?? '';
$descricao = $descricao ?? '';
$preco = $preco ?? '';
$categoria = $categoria ?? '';
$formaPagamento = $formaPagamento ?? '';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?> - ReVeste</title>
     <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
</head>


<header class="container-admin">
        <div class="topo-direita">
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
            <form action="../logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Sair</button>
            </form>
        </div>
        <nav class="menu-adm">
            <a href="../dashboard.php">Dashboard</a>
            <a href="../produtos/listar.php">Produtos</a>
            <a href="../usuarios/listar.php">Usuários</a>
        </nav>
        <div class="container-admin-banner">
            <a href="dashboard.php">
                <img src="../img/logo.png" alt="ReVeste" class="logo-admin">
            </a>
        </div>


    </header>
    <main>
        <div class="form-wrapper">
    <form action="cadastrar.php" method="post" enctype="multipart/form-data">
    </form>
</div>

    <div class="box-formulario">
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>

        <form action="salvar.php" method="POST" enctype="multipart/form-data">

    <div class="container">
    <div>
        <label for="nome">Nome</label>
        <input id="nome" name="nome" type="text" required value="<?= htmlspecialchars($nome) ?>">
    </div>

    <div>
        <label for="tamanho">Tamanho</label>
        <input id="tamanho" name="tamanho" type="number" required value="<?= htmlspecialchars($tamanho) ?>">
    </div>

    <div>
        <label for="tipo">Tipo</label>
        <input id="tipo" name="tipo" type="text" required value="<?= htmlspecialchars($tipo) ?>">
    </div>

    <div>
        <label for="categoria">Categoria</label>
        <select id="categoria" name="categoria" required>
            <option value="">Selecione...</option>
            <option value="1" <?= $categoria == 1 ? 'selected' : '' ?>>Roupas</option>
            <option value="2" <?= $categoria == 2 ? 'selected' : '' ?>>Calçados</option>
            <option value="3" <?= $categoria == 3 ? 'selected' : '' ?>>Acessórios</option>
        </select>
    </div>

    <div>
        <label for="nome">Descrição</label>
        <input id="descricao" name="descricao" type="text" required value="<?= htmlspecialchars($descricao) ?>">
    </div>

    <div>
        <label for="preco">Preço</label>
        <input id="preco" name="preco" type="number" step="0.01" required value="<?= htmlspecialchars($preco) ?>">
    </div>

    <div>
        <label for="formaPagamento">Forma de Pagamento</label>
        <select id="formaPagamento" name="formaPagamento" required>
            <option value="">Selecione...</option>
            <option value="Crédito" <?= $formaPagamento == 'Crédito' ? 'selected' : '' ?>>Crédito</option>
            <option value="Débito" <?= $formaPagamento == 'Débito' ? 'selected' : '' ?>>Débito</option>
            <option value="Pix" <?= $formaPagamento == 'Pix' ? 'selected' : '' ?>>Pix</option>
        </select>
    </div>       

            <!-- Novo campo: imagem -->
            <div>
                <label for="imagem">Imagem do produto</label>
                <input id="imagem" name="imagem" type="file" accept="image/*">
                <?php if (!empty($valorImagem)): ?>
                    <div class="preview-imagem">
                        <!-- Ajuste o caminho conforme onde você armazena as imagens (ex: ../uploads/) -->
                        <p>Imagem atual: <?= htmlspecialchars($valorImagem) ?></p>
                        <img src="<?= htmlspecialchars('../uploads/' . $valorImagem) ?>" alt="Imagem do produto" style="max-width:200px;display:block;margin-top:8px;">
                        <!-- Mantém o nome da imagem atual caso o usuário não envie nova -->
                        <input type="hidden" name="imagem_existente" value="<?= htmlspecialchars($valorImagem) ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="grupo-botoes">
                <button type="submit" class="botao-cadastrar"><?= htmlspecialchars($textoBotao) ?></button>
                <a href="listar.php" class="botao-voltar">Voltar</a>
            </div>
        </div>
    </div>
        </form>
    </main>
</body>

</html>