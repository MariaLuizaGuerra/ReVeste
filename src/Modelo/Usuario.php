<?php

class Usuario
{
    private ?int $id;
    private string $nome;
    private string $perfil;
    private string $email;
    private string $senha;

    public function __construct(?int $id, string $nome, string $perfil, string $email, string $senha)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->perfil = $perfil;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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
}