<?php

class UsuarioRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto(array $d): Usuario
    {
        return new Usuario(
            isset($d['id']) ? (int)$d['id'] : null,
            $d['nome']   ?? '',
            $d['perfil'] ?? 'User',
            $d['email']  ?? '',
            $d['senha']  ?? ''
        );
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT id,nome,perfil,email,senha FROM usuarios ORDER BY email";
        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscar(int $id): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id,nome,perfil,email,senha FROM usuarios WHERE id=?");
        $st->execute([$id]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function buscarPorEmail(string $email): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id,nome,perfil,email,senha FROM usuarios WHERE email=? LIMIT 1");
        $st->bindValue(1, $email);
        $st->execute([$email]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function salvar(Usuario $usuario)
    {
        $sql = "INSERT INTO usuarios (nome, perfil, email, senha) VALUES (?, ?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $usuario->getNome());
        $statement->bindValue(2, $usuario->getPerfil());
        $statement->bindValue(3, $usuario->getEmail());
        $statement->bindValue(4, password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
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

        $sql = "UPDATE usuarios SET nome = ?, perfil = ?, email = ?, senha = ? WHERE id = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $usuario->getNome(),
            $usuario->getPerfil(),
            $usuario->getEmail(),
            $senha,
            $usuario->getId()
        ]);
    }

    public function deletar(int $id): bool
    {
        $st = $this->pdo->prepare("DELETE FROM usuarios WHERE id=?");
        return $st->execute([$id]);
    }
}
