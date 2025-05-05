<?php
namespace App\Models\DAO;
use App\Models\Entidades\Fornecedor;
//peciso chamar a entidade que estou travalhando
class FornecedorDAO extends BaseDAO {

    public function verificaCnpj($cnpj)
    {
        try {

            $query = $this->select(
                "SELECT * FROM fornecedor WHERE cnpj = '$cnpj' "
            );

            return $query->fetch();

        }catch (Exception $e){
            throw new \Exception("Erro no acesso aos dados.", 500);
        }
    }    

    public function salvar(Fornecedor $fornecedor) {
        try {
            $nome = $fornecedor->getNome();
            $nomeFantasia = $fornecedor->getNomeFantasia();
            $cnpj = $fornecedor->getCnpj();
            $inscricaoEstadual = $fornecedor->getInscricaoEstadual();
            $endereco = $fornecedor->getEndereco();
            $tipoDeServico = $fornecedor->getTipoDeServico();
            $telefone = $fornecedor->getTelefone();

            return $this->insert(
                'fornecedor', 
                ":nome, :nomeFantasia, :cnpj, :inscricaoEstadual, :endereco, :tipoDeServico, :telefone ",[
                    ':nome'=>$nome,
                    ':nomeFantasia'=>$nomeFantasia,
                    ':cnpj'=>$cnpj,
                    ':inscricaoEstadual'=>$inscricaoEstadual,
                    ':endereco'=>$endereco,
                    ':tipoDeServico'=>$tipoDeServico,
                    ':telefone'=>$telefone
                ]
            );
            
        }catch (\Exception $e){
            throw new \Exception("Erro na gravação de dados.", 500);
        }
    }
}