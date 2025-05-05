<?php
namespace App\Models\Entidades;
class Cliente
{
    private $id;
    private $nome;
    private $dtnasc;
    private $cpf;
    private $telefone;


    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getNome(){
        return $this->nome;
    }
    public function setNome($nome){
        $this->nome = $nome;
    }
    public function getDtnasc(){
        return $this->dtnasc;
    }
    public function setDtnasc($dtnasc){
        $this->dtnasc = $dtnasc;
    }
    public function getCpf(){
        return $this->cpf;
    }
    public function setCpf($cpf){
        $this->cpf = $cpf;
    }
    public function getTelefone(){
        return $this->telefone;
    }
    public function setTelefone($telefone){
        $this->telefone = $telefone;
    }
}