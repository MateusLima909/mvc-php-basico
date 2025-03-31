<?php
namespace App\Models\Entidades;
class Fornecedor
{
    private $id;
    private $nome;
    private $nomeFantasia;
    private $cnpj;
    private $inscricaoEstadual;
    private $endereco;
    private $tipoDeServico;
    private $telefone;

    public function getid(){
        return $this->id;
    }
    public function setid($id){
        $this->id =$id;
    }
    public function getnome(){
        return $this->nome;
    }
    public function setnome($nome){
        $this->nome = $nome;
    }
    public function getnomeFantasia(){
        return $this->nomeFantasia;
    }
    public function setnomeFantasia($nomeFantasia){
        $this->nomeFantasia = $nomeFantasia;
    }
    public function getcnpj(){
        return $this->cnpj;
    }
    public function setcnpj($cnpj){
        $this->cnpj = $cnpj;
    }
    public function getinscricaoEstadual(){
        return $this->inscricaoEstadual;
    }
    public function setinscricaoEstadual($inscricaoEstadual){
        $this->inscricaoEstadual =$inscricaoEstadual;
    }
    public function getendereco(){
        return $this->endereco;
    }
    public function setendereco($endereco){
        $this->endereco = $endereco;
    }
    public function gettipoDeServico(){
        return $this->tipoDeServico;
    }
    public function settipoDeServico($tipoDeServico){
        $this->tipoDeServico = $tipoDeServico;
    }
    public function gettelefone(){
        return $this->telefone;
    }
    public function settelefone($telefone){
        $this->telefone = $telefone;
    }
}