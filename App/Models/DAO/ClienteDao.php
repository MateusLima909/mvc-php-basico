<?php
namespace App\Models\DAO;
use App\Models\Entidades\Cliente;
//peciso chamar a entidade que estou travalhando
class ClienteDAO extends BaseDAO{
    public function salvar(Cliente$cliente) {
        try {
            $nome = $cliente->getNome();
            $telefone = $cliente->getTelefone();
            $datanascimento = $cliente-getDTNasc();
            $cpf = $cliente->getCPF();

            return $this->insert(
                'cliente', ":nome, telefone, :datanascimento, :cpf",[
                    ':nome'=>$nome,
                    ':telefone'=>$telefone,
                    ':datanascimento'=>$datanascimento,
                    ':cpf'=>$cpf
                ]
                );
        }catch (\Exception $e){
            throw new \Exception("Erro na gravação de dados.", 500);
        }
    }
}