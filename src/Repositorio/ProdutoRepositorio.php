<?php

class ProdutoRepositorio
{
    private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto($dados)
    {
        return new Produto(
            $dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']
        );
    }

    public function opcoesCafe(): array
    {
        $sql1 = "SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco";
        $statement = $this->pdo->query($sql1);
        $produtosCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(function ($cafe) {
            return $this->formarObjeto($cafe);
        }, $produtosCafe);

        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql2 = "SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco";
        $statement = $this->pdo->query($sql2);
        $produtosAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function ($almoco) {
            return $this->formarObjeto($almoco);
        }, $produtosAlmoco);

        return  $dadosAlmoco;
    }

    public function buscarTodos()
    {
        $sql = "SELECT * FROM produtos ORDER BY preco";
        $statement = $this->pdo->query($sql);
        $dados = $statement->fetchAll(PDO::FETCH_ASSOC);

        $todosOsDados = array_map(function ($produto) {
            return $this->formarObjeto($produto);
        }, $dados);

        return $todosOsDados;
    }

    public function deletar(int $id)
    {
        // obtém nome da imagem antes de apagar o registro
        $sql = "SELECT imagem FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagem = $dados['imagem'] ?? null;

        // deleta o registro do banco
        $sqlDel = "DELETE FROM produtos WHERE id = ?";
        $stmtDel = $this->pdo->prepare($sqlDel);
        $stmtDel->bindValue(1, $id, PDO::PARAM_INT);
        $stmtDel->execute();

        // se excluiu no banco, tenta remover arquivo correspondente em uploads/
        if ($stmtDel->rowCount() > 0 && !empty($imagem)) {
            // não remover imagem padrão que está em img/
            if ($imagem === 'logo-granato.png') {
                return;
            }

            $caminho = __DIR__ . '/../../uploads/' . $imagem;
            if (is_file($caminho)) {
                @unlink($caminho); // suprime warnings; pode logar se necessário
            }
        }
    }

    public function salvar(Produto $produto)
    {
        $sql = "INSERT INTO produtos (tipo, nome, descricao, preco, imagem) VALUES (:tipo, :nome, :descricao, :preco, :imagem)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipo', $produto->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $produto->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $produto->getDescricao(), PDO::PARAM_STR);
        $stmt->bindValue(':preco', $produto->getPreco(), PDO::PARAM_STR);
        $imagem = $produto->getImagem();
        if ($imagem === null || $imagem === '') {
            // usa imagem padrão se coluna for NOT NULL
            $stmt->bindValue(':imagem', 'logo-granato.png', PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':imagem', $imagem, PDO::PARAM_STR);
        }
        $stmt->execute();
    }

    public function buscar(int $id)
    {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $dados = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->formarObjeto($dados);
    }

    public function atualizar(Produto $produto)
    {
        $sql = "UPDATE produtos SET tipo = :tipo, nome = :nome, descricao = :descricao, preco = :preco, imagem = :imagem WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipo', $produto->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $produto->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $produto->getDescricao(), PDO::PARAM_STR);
        $stmt->bindValue(':preco', $produto->getPreco(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $produto->getId(), PDO::PARAM_INT);

        $imagem = $produto->getImagem();
        if ($imagem === null || $imagem === '') {
            $stmt->bindValue(':imagem', 'logo-granato.png', PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':imagem', $imagem, PDO::PARAM_STR);
        }

        $stmt->execute();
    }
}
