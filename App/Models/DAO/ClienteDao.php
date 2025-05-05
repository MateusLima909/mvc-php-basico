<?php
namespace App\Models\DAO;
use App\Models\Entidades\Cliente;

class ClienteDAO extends BaseDAO{

    public function verificaCpf($cpf)
    {
        try {

            $query = $this->select(
                "SELECT * FROM cliente WHERE cpf = '$cpf' "
            );

            return $query->fetch();

        }catch (Exception $e){
            throw new \Exception("Erro no acesso aos dados.", 500);
        }
    }    

    public function salvar(Cliente $cliente) {
        try {
            $nome = $cliente->getNome();
            $telefone = $cliente->getTelefone();
            $dtnasc = $cliente->getDtnasc();
            $cpf = $cliente->getCpf();

            return $this->insert(
                'cliente', 
                ":nome, :telefone, :dtnasc, :cpf",[
                    ':nome'=>$nome,
                    ':telefone'=>$telefone,
                    ':dtnasc'=>$dtnasc,
                    ':cpf'=>$cpf
                ]
                );
        }catch (\Exception $e){
            throw new \Exception("Erro na gravação de dados.", 500);
        }
    }
}