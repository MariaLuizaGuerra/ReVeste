<?php

class ProdutoRepositorio
{
    private $pdo; // removido o tipo PDO para compatibilidade com PHP 7

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
            (int) $dados['categoria_id'],
            $dados['tamanho'],
            $dados['categoria'],
            $dados['imagem']
        );
    }

    public function opcoesRoupas(): array
    {
        $sql1 = "SELECT * FROM produtos WHERE tipo = 'Camiseta' ORDER BY preco";
        $statement = $this->pdo->query($sql1);
        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosRoupas = array_map(function ($roupa) {
            return $this->formarObjeto($roupa);
        }, $produtos);

        return $dadosRoupas;
    }

    public function opcoesAcessorios(): array
    {
        $sql2 = "SELECT * FROM produtos WHERE tipo = 'Acessorrio' ORDER BY preco";
        $statement = $this->pdo->query($sql2);
        $produtosAcessorios = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAcessorio = array_map(function ($almoco) {
            return $this->formarObjeto($almoco);
        }, $produtosAcessorios);

        return  $dadosAcessorio;
    }

    public function contarTotal(): int 
    {
        $sql = "SELECT COUNT(*) as total FROM produtos";
        $statement = $this->pdo->query($sql);
        $resultado = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    public function buscarPaginado($limite, $offset, $ordem = null, $direcao = 'ASC'): array
    {
        $colunasPermitidas = ['descricao', 'preco'];
        
        $sql = "SELECT * FROM produtos";
        
        if ($ordem !== null && in_array(strtolower($ordem), $colunasPermitidas)) {
            $direcao = strtoupper($direcao) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY {$ordem} {$direcao}";
        }
        
        $sql .= " LIMIT ? OFFSET ?";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $limite, PDO::PARAM_INT);
        $statement->bindValue(2, $offset, PDO::PARAM_INT);
        $statement->execute();

        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);
        $listaProdutos = [];

        foreach ($produtos as $produto) {
            $listaProdutos[] = $this->formarObjeto($produto);
        }

        return $listaProdutos;
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

    public function deletar($id)
    {
        $sql = "SELECT imagem FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagem = $dados['imagem'] ?? null;

        $sqlDel = "DELETE FROM produtos WHERE id = ?";
        $stmtDel = $this->pdo->prepare($sqlDel);
        $stmtDel->bindValue(1, $id, PDO::PARAM_INT);
        $stmtDel->execute();

        if ($stmtDel->rowCount() > 0 && !empty($imagem)) {
            if ($imagem === 'logo-granato.png') {
                return;
            }

            $caminho = __DIR__ . '/../../uploads/' . $imagem;
            if (is_file($caminho)) {
                @unlink($caminho);
            }
        }
    }

    public function salvar(Produto $produto)
    {
        $sql = "INSERT INTO produtos (tipo, nome, descricao, preco, categoria_id, tamanho, categoria, imagem)
        VALUES (:tipo, :nome, :descricao, :preco, :categoria_id, :tamanho, :categoria, :imagem)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipo', $produto->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $produto->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $produto->getDescricao(), PDO::PARAM_STR);
        $stmt->bindValue(':preco', $produto->getPreco(), PDO::PARAM_STR);
        $stmt->bindValue(':categoria_id', $produto->getCategoria_id(), PDO::PARAM_INT);
        $stmt->bindValue(':tamanho', $produto->getTamanho(), PDO::PARAM_STR);
        $stmt->bindValue(':categoria', $produto->getCategoria(), PDO::PARAM_STR);

        $imagem = $produto->getImagem();
        if ($imagem === null || $imagem === '') {
            $stmt->bindValue(':imagem', 'logo-granato.png', PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':imagem', $imagem, PDO::PARAM_STR);
        }
        $stmt->execute();
    }

    public function buscar($id)
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
        $sql = "UPDATE produtos SET tipo = :tipo, nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id, imagem = :imagem WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipo', $produto->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $produto->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $produto->getDescricao(), PDO::PARAM_STR);
        $stmt->bindValue(':preco', $produto->getPreco(), PDO::PARAM_STR);
        $stmt->bindValue(':categoria_id', $produto->getCategoria_id(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $produto->getId(), PDO::PARAM_INT);

        $imagem = $produto->getImagem();
        if ($imagem === null || $imagem === '') {
            $stmt->bindValue(':imagem', 'logo.png', PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':imagem', $imagem, PDO::PARAM_STR);
        }

        $stmt->execute();
    }
}
