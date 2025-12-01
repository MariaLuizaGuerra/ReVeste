<?php
class Produto
{
    private $id;
    private $tipo;
    private $nome;
    private $descricao;
    private $imagem;
    private $preco;
    private $categoria;
    private $categoria_id;
    private $tamanho;

    public function __construct(
        $id = null,
        $tipo,
        $nome,
        $descricao,
        $preco,
        $categoria_id = null,
        $tamanho = null,
        $categoria = null,
        $imagem = null
    ) {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria_id = $categoria_id;
        $this->tamanho = $tamanho;
        $this->categoria = $categoria;
        $this->imagem = $imagem ? $imagem : 'reVeste_Logo.jpg';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function getImagemDiretorio()
    {
        $uploadsPath = __DIR__ . '/../../uploads/';

        if ($this->imagem && file_exists($uploadsPath . $this->imagem)) {
            return 'uploads/' . $this->imagem;
        }

        return 'img/' . ($this->imagem ? $this->imagem : 'reVeste_Logo.jpg');
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function getCategoria_id()
    {
        return $this->categoria_id;
    }

    public function getPrecoFormatado()
    {
        return "R$ " . number_format($this->preco, 2);
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getTamanho()
    {
        return $this->tamanho;
    }
}
