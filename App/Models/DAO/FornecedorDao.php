<?php

namespace App\Models\DAO;

use App\Models\Entidades\Fornecedor; // Certifique-se que o caminho e nome da entidade estão corretos

class FornecedorDAO extends BaseDAO
{
    /**
     * Verifica se um CNPJ já existe no banco de dados.
     * @param string $cnpj O CNPJ a ser verificado.
     * @return bool Retorna true se o CNPJ existir, false caso contrário.
     * @throws \Exception Se ocorrer um erro durante a verificação.
     */
    public function verificaCnpj($cnpj)
    {
        try {
            // Seleciona 1 para eficiência, só precisamos saber se existe ou não
            $sql = "SELECT 1 FROM fornecedor WHERE cnpj = :cnpj LIMIT 1";
            
            // Supondo que $this->select() do BaseDAO foi corrigido para usar prepare/execute com params
            // e retorna um PDOStatement
            $stmt = $this->select($sql, [':cnpj' => $cnpj]);
            
            // fetchColumn() retorna o valor da primeira coluna da próxima linha ou false se não houver mais linhas.
            return (bool) $stmt->fetchColumn(); 
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->verificaCnpj: " . $e->getMessage());
            throw new \Exception("Erro ao verificar CNPJ no banco de dados.", 500, $e);
        }
    }

    /**
     * Salva um novo fornecedor no banco de dados.
     * @param Fornecedor $fornecedor O objeto Fornecedor a ser salvo.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a gravação.
     */
    public function salvar(Fornecedor $fornecedor)
    {
        try {
            // Supondo que $this->insert() do BaseDAO foi corrigido e espera um array associativo ['coluna' => 'valor']
            return $this->insert(
                'fornecedor', // Nome da tabela
                [
                    // As chaves são os nomes das colunas REAIS no banco
                    'nome' => $fornecedor->getNome(),
                    'nomeFantasia' => $fornecedor->getNomeFantasia(),
                    'cnpj' => $fornecedor->getCnpj(),
                    'inscricaoEstadual' => $fornecedor->getInscricaoEstadual(),
                    'endereco' => $fornecedor->getEndereco(),
                    'tipoDeServico' => $fornecedor->getTipoDeServico(),
                    'telefone' => $fornecedor->getTelefone()
                    // Se você adicionou a coluna id_usuario na tabela fornecedor e quer gerenciá-la aqui:
                    // 'id_usuario' => $fornecedor->getIdUsuario() // Certifique-se que getIdUsuario() existe e retorna o valor correto
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro ao gravar os dados do fornecedor.", 500, $e);
        }
    }

    /**
     * Atualiza os dados de um fornecedor existente no banco de dados.
     * @param Fornecedor $fornecedor O objeto Fornecedor com os dados atualizados.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a atualização.
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
                        telefone = :telefone
                        /* Se for atualizar id_usuario: */
                        /* , id_usuario = :id_usuario */
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
            // Se for atualizar id_usuario:
            // if ($fornecedor->getIdUsuario() !== null) {
            //     $stmt->bindValue(':id_usuario', $fornecedor->getIdUsuario(), \PDO::PARAM_INT);
            // } else {
            //     $stmt->bindValue(':id_usuario', null, \PDO::PARAM_NULL);
            // }

            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Erro em FornecedorDAO->atualizar: " . $e->getMessage());
            throw new \Exception("Erro ao atualizar os dados do fornecedor.", 500, $e);
        }
    }

    /**
     * Exclui um fornecedor do banco de dados pelo ID.
     * @param int $id O ID do fornecedor a ser excluído.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a exclusão.
     */
    public function excluir($id)
    {
        try {
            $idParaExcluir = is_array($id) ? ($id[0] ?? null) : $id;

            if ($idParaExcluir === null) {
                throw new \InvalidArgumentException("ID para exclusão do fornecedor não pode ser nulo.");
            }

            $sql = "DELETE FROM fornecedor WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Erro em FornecedorDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro ao excluir o fornecedor.", 500, $e);
        } catch (\InvalidArgumentException $e) {
             error_log("Erro em FornecedorDAO->excluir: " . $e->getMessage());
            throw $e; 
        }
    }

    /**
     * Lista todos os fornecedores cadastrados.
     * @return array Uma lista de objetos Fornecedor.
     * @throws \Exception Se ocorrer um erro durante a listagem.
     */
    public function listar()
    {
        try {
            $sql = "SELECT * FROM fornecedor ORDER BY nome ASC";
            // Supondo que $this->select() do BaseDAO foi corrigido e retorna PDOStatement
            $stmt = $this->select($sql); 
            return $stmt->fetchAll(\PDO::FETCH_CLASS, \App\Models\Entidades\Fornecedor::class);
        } catch (\Exception $e) {
            error_log("Erro em FornecedorDAO->listar: " . $e->getMessage());
            throw new \Exception("Erro ao listar os fornecedores.", 500, $e);
        }
    }

    /**
     * Busca um fornecedor pelo ID.
     * @param int $id O ID do fornecedor a ser buscado.
     * @return Fornecedor|false Um objeto Fornecedor se encontrado, false caso contrário.
     * @throws \Exception Se ocorrer um erro durante a busca.
     */
    public function buscar($id)
    {
        try {
            $idParaBuscar = is_array($id) ? ($id[0] ?? null) : $id;

            if ($idParaBuscar === null) {
                 throw new \InvalidArgumentException("ID para busca do fornecedor não pode ser nulo.");
            }

            $sql = "SELECT * FROM fornecedor WHERE id = :id";
            $stmt = $this->conexao->prepare($sql); // Usando prepare diretamente da conexão
            $stmt->bindValue(':id', $idParaBuscar, \PDO::PARAM_INT);
            $stmt->execute();

            // Retorna um objeto da classe Fornecedor ou false se não encontrar
            return $stmt->fetchObject(\App\Models\Entidades\Fornecedor::class); 

        } catch (\PDOException $e) { 
            error_log("Erro em FornecedorDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar fornecedor por ID.", 500, $e);
        } catch (\InvalidArgumentException $e) {
            error_log("Erro em FornecedorDAO->buscar: " . $e->getMessage());
            throw $e;
        }
    }
}
