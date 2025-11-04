<!-- <?php
        require __DIR__ . "/../src/conexao-bd.php";
        require __DIR__ . "/../src/Modelo/Usuario.php";
        require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

        $usuarioRepositorio = new UsuarioRepositorio($pdo);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Se há um ID no POST, é uma atualização. Senão, é um cadastro.
            $usuario = new Usuario(
                $_POST['id'] ?: null, // Operador ternário para definir o ID
                $_POST['nome'],
                $_POST['perfil'],
                $_POST['email'],
                $_POST['senha'],
            );
        }

        if ($usuario->getId()) {
            // Se tem ID, atualiza
            $usuarioRepositorio->atualizar($usuario);
        } else {
            // Se não tem ID, salva um novo
            $usuarioRepositorio->salvar($usuario);
        }

        header("Location: listar.php");
        exit();


        require __DIR__ . "/../src/conexao-bd.php";
        require __DIR__ . "/../src/Modelo/Usuario.php";
        require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

        $repo = new UsuarioRepositorio($pdo);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: listar.php');
            exit;
        }

        $id     = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
        $nome   = trim($_POST['nome']   ?? '');
        $perfil = trim($_POST['perfil'] ?? 'User');
        $email  = trim($_POST['email']  ?? '');
        $senha  = $_POST['senha'] ?? '';

        if ($nome === '' || $email === '' || (!$id && $senha === '')) {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
            exit;
        }

        if (!in_array($perfil, ['User', 'Admin'], true)) {
            $perfil = 'User';
        }

        if ($id) {
            // Edição
            $existente = $repo->buscar($id);
            if (!$existente) {
                header('Location: listar.php?erro=inexistente');
                exit;
            }

            // Se senha em branco: manter hash atual
            if ($senha === '') {
                $senhaParaObjeto = $existente->getSenha(); // já é hash
            } else {
                $senhaParaObjeto = $senha; // plain; será hash no repositório (com proteção contra re-hash)
            }

            $usuario = new Usuario($id, $nome, $perfil, $email, $senhaParaObjeto);
            $repo->atualizar($usuario);
            header('Location: listar.php?ok=1');
            exit;
        } else {
            // Novo usuário
            $usuario = new Usuario(null, $nome, $perfil, $email, $senha); // plain password
            $repo->salvar($usuario);
            header('Location: listar.php?novo=1');
            exit;
        }
