<?php

// É necessário importar a classe PDO se ela estiver sendo usada com 'use' em outras partes do seu código
// use PDO; 
// use ReVeste\Modelo\Usuario; // Assumindo que a classe Usuario está sendo usada

class UsuarioRepositorio
{
    private $pdo; // <--- CORREÇÃO APLICADA: Removido 'PDO' aqui (Linha 5)

    // O tipo PDO no construtor (public function __construct(PDO $pdo)) é suportado pelo PHP >= 7.0 e pode permanecer.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

   private function formarObjeto(array $d): Usuario
{
    return new Usuario(
        isset($d['id']) ? (int)$d['id'] : null,
        $d['nome'] ?? '',
        $d['email'] ?? '',
        $d['perfil'] ?? '',
        $d['senha'] ?? '',
        $d['data_nascimento'] ?? '',   // ✔ CORRIGIDO
        $d['sexo'] ?? '',
        $d['telefone'] ?? '',
        $d['endereco'] ?? '',
        $d['numero'] ?? '',
        $d['cidade'] ?? '',
        $d['estado'] ?? ''
    );
}


    // Versão Antiga da função buscarTodos() para PHP < 7.4

    public function buscarTodos(): array
    {
        $sql = "SELECT id,nome,email,senha,perfil, data_nascimento, telefone, sexo, endereco, numero, cidade, estado  FROM usuarios ORDER BY email";
        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
        // Substituído 'fn' pela função anônima tradicional
        return array_map(function($r) {
            return $this->formarObjeto($r);
        }, $rs);
    }

    public function buscar(int $id): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id,nome,email,senha,perfil, data_nascimento, telefone, sexo, endereco, numero, cidade, estado  FROM usuarios WHERE id=?");
        $st->execute([$id]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function buscarPorEmail(string $email): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id, nome, perfil, email, senha, data_nascimento, telefone, sexo, endereco, numero, cidade, estado FROM usuarios WHERE email = ? LIMIT 1");
        $st->execute([$email]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    // C:\xampp\htdocs\ReVeste\src\Repositorio\UsuarioRepositorio.php

    public function salvar(Usuario $usuario)
    {
    // A QUERY DEVE LISTAR 11 CAMPOS (sem o ID) NA ORDEM DO OBJETO:
        $sql = "INSERT INTO usuarios (nome, perfil, email, senha, data_nascimento, sexo2, telefone, endereco, numero, cidade, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        $statement = $this->pdo->prepare($sql);
    
    // 1. nome
        $statement->bindValue(1, $usuario->getNome());
    // 2. perfil
        $statement->bindValue(2, $usuario->getPerfil());
    // 3. email
        $statement->bindValue(3, $usuario->getEmail());
    // 4. senha (COM HASH)
        $statement->bindValue(4, password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
    // 5. dataNascimento
        $statement->bindValue(5, $usuario->getData_nascimento());
    // 6. sexo2 (CORRIGIDO PARA USAR O NOME DA COLUNA DO DB)
        $statement->bindValue(6, $usuario->getSexo()); 
    // 7. telefone
        $statement->bindValue(7, $usuario->getTelefone()); 
    // 8. endereco
        $statement->bindValue(8, $usuario->getEndereco()); 
    // 9. numero
        $statement->bindValue(9, $usuario->getNumero()); 
    // 10. cidade
        $statement->bindValue(10, $usuario->getCidade()); 
    // 11. estado
        $statement->bindValue(11, $usuario->getEstado()); 
    
        $statement->execute();
}

    public function autenticar(string $email, string $senha): bool
    {
        $u = $this->buscarPorEmail($email);
        return $u ? password_verify($senha, $u->getSenha()) : false;
    }

    public function atualizar(Usuario $usuario)
    {
        $senha = $usuario->getSenha();
        // Se não parecer hash bcrypt (outra estratégia: também aceitar argon2)
        if (!preg_match('/^\$2y\$/', $senha)) {
            $senha = password_hash($senha, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, perfil = ?, dataNascimento = ?, telefone = ?, genero = ?, endereco = ?, numero = ?, cidade = ?, estado = ?  WHERE id = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $senha,
            $usuario->getPerfil(),
            $usuario->getDataNascimento(),
            $usuario->getTelefone(),
            $usuario->getGenero(),
            $usuario->getEndereco(),
            $usuario->getNumero(),
            $usuario->getCidade(),
            $usuario->getEstado(),
            $usuario->getId()
        ]);
    }

    public function deletar(int $id): bool
    {
        $st = $this->pdo->prepare("DELETE FROM usuarios WHERE id=?");
        return $st->execute([$id]);
    }
}