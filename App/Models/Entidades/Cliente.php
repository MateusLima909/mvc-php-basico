<?php
namespace App\Models\Entidades;
class Cliente
{
    private $id;
    private $nome;
    private $dtnasc;
    private $cpf;
    private $telefone;


    public function getid(){
        return $this->id;
    }
    public function setid($id){
        $this->id = $id;
    }
    public function getnome(){
        return $this->nome;
    }
    public function setnome($nome){
        $this->nome = $nome;
    }
    public function getdtnasc(){
        return $this->dtnasc;
    }
    public function setdtnasc($dtnasc){
        $this->dtnasc = $dtnasc;
    }
    public function getcpf(){
        return $this->cpf;
    }
    public function setcpf($cpf){
        $this->cpf = $cpf;
    }
    public function gettelefone(){
        return $this->telefone;
    }
    public function settelefone(){
        $this->telefone = $telefone;
    }
    public function getfonecedor(){
        return $this->fornecedor;
    }
    public function setfornecedor(){
        $this->fornecedor = $fornecedor;
    }
}