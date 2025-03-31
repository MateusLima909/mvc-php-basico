<?php
namespace App\Models\DAO;
use App\Models\Entidades\Fornecedor;
//peciso chamar a entidade que estou travalhando
class FornecedorDAO extends BaseDAO{
    public function salvar(Fornecedor$fornecedor) {
        try {
            $nome = $fornecedor->getNome();
            $telefone = $fornecedor->getTelefone();
            $datanascimento = $fornecedor-getendereco();
            $cpf = $fornecedor->getCNPJ();

            return $this->insert(
                'fornecedor', ":nome, telefone, :endereco, :cnpj",[
                    ':nome'=>$nome,
                    ':telefone'=>$telefone,
                    ':endereco'=>$endereco,
                    ':cnpj'=>$cnpj
                ]
                );
        }catch (\Exception $e){
            throw new \Exception("Erro na gravação de dados.", 500);
        }
    }
}