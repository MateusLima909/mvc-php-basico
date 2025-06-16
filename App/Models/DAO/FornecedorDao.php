<?php

namespace App\Models\DAO;

use App\Models\Entidades\Fornecedor;

class FornecedorDAO extends BaseDAO
{
    /**
     * Verifica se um CNPJ já existe na base de dados.
     * @param string $cnpj O CNPJ a ser verificado.
     * @return bool Retorna true se o CNPJ existir, false caso contrário.
     */
    public function verificaCnpj($cnpj)
    {
        try {
            $sql = "SELECT 1 FROM fornecedor WHERE cnpj = :cnpj LIMIT 1";
            $stmt = $this->select($sql, [':cnpj' => $cnpj]);
            return (bool) $stmt->fetchColumn(); 
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->verificaCnpj: " . $e->getMessage());
            throw new \Exception("Erro ao verificar CNPJ na base de dados.", 500, $e);
        }
    }

    /**
     * Guarda um novo fornecedor na base de dados.
     * @param Fornecedor $fornecedor O objeto Fornecedor a ser guardado.
     * @return int|false O ID do novo fornecedor ou false em caso de falha.
     */
    public function salvar(Fornecedor $fornecedor)
    {
        try {
            return $this->insert(
                'fornecedor',
                [
                    'nome'              => $fornecedor->getNome(),
                    'nomeFantasia'      => $fornecedor->getNomeFantasia(),
                    'cnpj'              => $fornecedor->getCnpj(),
                    'inscricaoEstadual' => $fornecedor->getInscricaoEstadual(),
                    'endereco'          => $fornecedor->getEndereco(),
                    'tipoDeServico'     => $fornecedor->getTipoDeServico(),
                    'telefone'          => $fornecedor->getTelefone(),
                    'id_usuario'        => $fornecedor->getIdUsuario() 
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro ao gravar os dados do fornecedor.", 500, $e);
        }
    }

    /**
     * Atualiza os dados de um fornecedor existente na base de dados.
     * @param Fornecedor $fornecedor O objeto Fornecedor com os dados atualizados.
     * @return int O número de linhas afetadas.
     */
    public function atualizar(Fornecedor $fornecedor)
    {
        try {
            $sql = "UPDATE fornecedor 
                    SET nome = :nome, 
                        nomeFantasia = :nomeFantasia, 
                        cnpj = :cnpj, 
                        inscricaoEstadual = :inscricaoEstadual, 
                        endereco = :endereco, 
                        tipoDeServico = :tipoDeServico, 
                        telefone = :telefone,
                        id_usuario = :id_usuario
                    WHERE id = :id";
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindValue(':id', $fornecedor->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':nome', $fornecedor->getNome());
            $stmt->bindValue(':nomeFantasia', $fornecedor->getNomeFantasia());
            $stmt->bindValue(':cnpj', $fornecedor->getCnpj());
            $stmt->bindValue(':inscricaoEstadual', $fornecedor->getInscricaoEstadual());
            $stmt->bindValue(':endereco', $fornecedor->getEndereco());
            $stmt->bindValue(':tipoDeServico', $fornecedor->getTipoDeServico());
            $stmt->bindValue(':telefone', $fornecedor->getTelefone());
            $stmt->bindValue(':id_usuario', $fornecedor->getIdUsuario(), \PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Erro em FornecedorDAO->atualizar: " . $e->getMessage());
            throw new \Exception("Erro ao atualizar os dados do fornecedor.", 500, $e);
        }
    }

    /**
     * Exclui um fornecedor da base de dados pelo ID.
     * @param int $id O ID do fornecedor a ser excluído.
     * @return int O número de linhas afetadas.
     */
    public function excluir($id)
    {
        try {
            $idParaExcluir = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaExcluir === null) {
                throw new \InvalidArgumentException("ID para exclusão não pode ser nulo.");
            }
            $sql = "DELETE FROM fornecedor WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException | \InvalidArgumentException $e) {
            error_log("Erro em FornecedorDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro ao excluir o fornecedor.", 500, $e);
        }
    }

    /**
     * Lista todos os fornecedores registados.
     * @return array Uma lista de objetos Fornecedor.
     */
    public function listar()
    {
        try {
            $sql = "SELECT * FROM fornecedor ORDER BY nome ASC";
            $stmt = $this->select($sql); 
            return $stmt->fetchAll(\PDO::FETCH_CLASS, \App\Models\Entidades\Fornecedor::class);
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->listar: " . $e->getMessage());
            throw new \Exception("Erro ao listar os fornecedores.", 500, $e);
        }
    }

    /**
     * Busca um fornecedor pelo seu ID.
     * @param int $id O ID do fornecedor.
     * @return Fornecedor|false
     */
    public function buscar($id)
    {
        try {
            $idParaBuscar = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaBuscar === null) {
                 throw new \InvalidArgumentException("ID para busca não pode ser nulo.");
            }
            $sql = "SELECT * FROM fornecedor WHERE id = :id";
            $stmt = $this->conexao->prepare($sql); 
            $stmt->bindValue(':id', $idParaBuscar, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Fornecedor::class); 
        } catch (\PDOException | \InvalidArgumentException $e) { 
            error_log("Erro em FornecedorDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar fornecedor por ID.", 500, $e);
        }
    }
    
    /**
     * Busca um fornecedor pelo ID do utilizador associado.
     * @param int $idUsuario O ID do utilizador.
     * @return Fornecedor|false
     */
    public function buscarPorIdUsuario($idUsuario)
    {
        try {
            $sql = "SELECT * FROM fornecedor WHERE id_usuario = :id_usuario";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id_usuario', $idUsuario, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Fornecedor::class);
        } catch (\PDOException $e) {
            error_log("Erro em FornecedorDAO->buscarPorIdUsuario: " . $e->getMessage());
            throw new \Exception("Erro ao buscar fornecedor por ID de utilizador.", 500, $e);
        }
    }
}
