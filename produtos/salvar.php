<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Produto.php";
require __DIR__ . "/../src/Repositorio/ProdutoRepositorio.php";

$produtoRepositorio = new ProdutoRepositorio($pdo);

// --- LÓGICA DE PROCESSAMENTO DO FORMULÁRIO (POST) ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Se há um ID no POST, é uma atualização. Senão, é um cadastro.
    $id = !empty($_POST['id']) ? $_POST['id'] : null;
    $produto = new Produto(
        $id,
        $_POST['tipo'],
        $_POST['tamanho'],
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['formaPagamento'],
        $_POST['preco']
    );

    // Lógica de upload de imagem (validação + armazenamento)
    $uploadsDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $_FILES['imagem']['tmp_name'];

        // Verifica se o arquivo é uma imagem válida
        $imgInfo = @getimagesize($tmpPath);
        if ($imgInfo !== false) {
            // Define extensão a partir do mime type
            $mime = $imgInfo['mime'];
            $ext = '';
            switch ($mime) {
                case 'image/jpeg':
                    $ext = '.jpg';
                    break;
                case 'image/png':
                    $ext = '.png';
                    break;
                case 'image/gif':
                    $ext = '.gif';
                    break;
                default:
                    $ext = image_type_to_extension($imgInfo[2]) ?: '';
            }

            // Gera nome seguro e único
            $filename = uniqid('img_', true) . $ext;
            $destination = $uploadsDir . $filename;

            if (move_uploaded_file($tmpPath, $destination)) {
                $produto->setImagem($filename);
            } else {
                // falha no move, manter comportamento: usar imagem existente ou padrão
            }
        } else {
            // Não é imagem válida -> ignorar upload (poderia registrar erro)
        }
    } elseif (!empty($_POST['imagem_existente'])) {
        // Mantém a imagem anterior se não houve upload novo
        $produto->setImagem($_POST['imagem_existente']);
    } else {
        // Nenhuma imagem enviada nem existente -> usa imagem padrão (coluna é NOT NULL)
        $produto->setImagem('logo-granato.png');
    }

    if ($produto->getId()) {
        // Se tem ID, atualiza
        $produtoRepositorio->atualizar($produto);
    } else {
        // Se não tem ID, salva um novo
        $produtoRepositorio->salvar($produto);
    }

    header("Location: listar.php");
    exit(); // É uma boa prática usar exit() após um redirecionamento
}
