<?php
    //Classe de persistência no BD
    class UsuarioRepositorio
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        private function formarObjeto(array $dados): Usuario
        {
            return new Produto((int)$dados['id'], $dados['nome'], $dados['tipo'], $dados['tamanho'], $dados['descricao'], $dados['preco']);
        }

        public function buscarPorNome(string $nome): ?Produto
        {
            $sql = "SELECT id, nome, tipo, tamanho, descricao, preco FROM usuarios WHERE nome =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $nome);
            $stmt->execute();
            $dados = $stmt->fetch();
            return $dados ? $this->formarObjeto($dados): null ;
        }

        public function salvar(Produto $produto): void
        {
            $sql = "INSERT INTO produto (nome, tipo, senha) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $produto->getNome());
            $stmt->bindValue(2, $produto->getTipo());
            $stmt->bindValue(3, $produto->getTamanho());
            $stmt->execute();
        }
    }

?>