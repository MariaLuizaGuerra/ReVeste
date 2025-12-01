<?php

class Usuario
{
    private $id;
    private $nome;
    private $perfil; 
    private $email;
    private $senha;
    private $data_nascimento;
    private $sexo; 
    private $telefone;
    private $endereco;
    private $numero;
    private $cidade;
    private $estado;

    
   public function __construct(
    $id = null, 
    string $nome, 
    string $email, 
    string $perfil, 
    string $senha,
    string $data_nascimento,
    string $sexo,
    string $telefone,
    string $endereco,
    string $numero,
    string $cidade,
    string $estado
) 
{
    $this->id = $id;
    $this->nome = $nome;
    $this->email = $email;
    $this->perfil = $perfil; 
    $this->senha = $senha;
    $this->data_nascimento = $data_nascimento;
    $this->sexo = $sexo;
    $this->telefone = $telefone;
    $this->endereco = $endereco;
    $this->numero = $numero;
    $this->cidade = $cidade;
    $this->estado = $estado;
}

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getPerfil(): string
    {
        return $this->perfil;

    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function getData_nascimento(): string
    {
        return $this->data_nascimento;
    }

    public function getSexo(): string
    {
        return $this->sexo;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function getEndereco(): string
    {
        // Se precisar de um endereço completo formatado:
        // return $this->endereco . ', ' . $this->numero . ', ' . $this->cidade . ' - ' . $this->estado;
        return $this->endereco; 
    }
    
    public function getNumero(): string
    {
        return $this->numero;
    }
    
    public function getCidade(): string
    {
        return $this->cidade;
    }
    
    public function getEstado(): string
    {
        return $this->estado;
    }
}