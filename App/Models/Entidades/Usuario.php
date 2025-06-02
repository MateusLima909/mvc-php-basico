<?php

namespace App\Models\Entidades;

class Usuario {
    
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $nivel_acesso;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    { 
        return $this->senha; 
    }

    public function setSenha($senha) 
    { 
        $this->senha = $senha; 
    }

    public function getNivelAcesso() 
    { 
        return $this->nivel_acesso; 
    }

    public function setNivelAcesso($nivel_acesso) 
    { 
        $this->nivel_acesso = $nivel_acesso; 
    }
}