<?php

namespace App\Models\DAO;

use App\Models\Entidades\Cliente;

class ClienteDAO extends BaseDAO
{
    /**
     * Verifica se um CPF já existe no banco de dados.
     * @param string $cpf O CPF a ser verificado.
     * @return bool Retorna true se o CPF existir, false caso contrário.
     * @throws \Exception Se ocorrer um erro durante a verificação.
     */
    public function verificaCpf($cpf)
    {
        try {
            $sql = "SELECT 1 FROM cliente WHERE cpf = :cpf LIMIT 1";
            $stmt = $this->select($sql, [':cpf' => $cpf]);
            return (bool) $stmt->fetchColumn(); 
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->verificaCpf: " . $e->getMessage());
            throw new \Exception("Erro ao verificar CPF no banco de dados.", 500, $e);
        }
    }

    /**
     * Salva um novo cliente no banco de dados.
     * @param Cliente $cliente O objeto Cliente a ser salvo.
     * @return int|false O ID do novo cliente ou false em caso de falha.
     * @throws \Exception Se ocorrer um erro durante a gravação.
     */
    public function salvar(Cliente $cliente)
    {
        try {
            // Supondo que $this->insert() do BaseDAO retorna o último ID inserido
            return $this->insert(
                'cliente',
                [
                    'nome'       => $cliente->getNome(),
                    'dtnasc'     => $cliente->getDtnasc(),
                    'cpf'        => $cliente->getCpf(),
                    'telefone'   => $cliente->getTelefone(),
                    'id_usuario' => $cliente->getIdUsuario() 
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro ao gravar os dados do cliente.", 500, $e);
        }
    }

    /**
     * Atualiza os dados de um cliente existente no banco de dados.
     * @param Cliente $cliente O objeto Cliente com os dados atualizados.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a atualização.
     */
    public function atualizar(Cliente $cliente)
    {
        try {
            $sql = "UPDATE cliente 
                    SET nome = :nome, 
                        dtnasc = :dtnasc, 
                        cpf = :cpf, 
                        telefone = :telefone,
                        id_usuario = :id_usuario
                    WHERE id = :id";
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindValue(':id', $cliente->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':nome', $cliente->getNome());
            $stmt->bindValue(':dtnasc', $cliente->getDtnasc());
            $stmt->bindValue(':cpf', $cliente->getCpf());
            $stmt->bindValue(':telefone', $cliente->getTelefone());
            $stmt->bindValue(':id_usuario', $cliente->getIdUsuario(), \PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Erro em ClienteDAO->atualizar: " . $e->getMessage());
            throw new \Exception("Erro ao atualizar os dados do cliente.", 500, $e);
        }
    }

    /**
     * Exclui um cliente do banco de dados pelo ID.
     * @param int $id O ID do cliente a ser excluído.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a exclusão.
     */
    public function excluir($id)
    {
        try {
            $idParaExcluir = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaExcluir === null) {
                throw new \InvalidArgumentException("ID para exclusão não pode ser nulo.");
            }
            $sql = "DELETE FROM cliente WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException | \InvalidArgumentException $e) {
            error_log("Erro em ClienteDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro ao excluir o cliente.", 500, $e);
        }
    }

    /**
     * Lista todos os clientes cadastrados.
     * @return array Uma lista de objetos Cliente.
     * @throws \Exception Se ocorrer um erro durante a listagem.
     */
    public function listar()
    {
        try {
            $sql = "SELECT * FROM cliente ORDER BY nome ASC";
            $stmt = $this->select($sql); 
            return $stmt->fetchAll(\PDO::FETCH_CLASS, \App\Models\Entidades\Cliente::class);
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->listar: " . $e->getMessage());
            throw new \Exception("Erro ao listar os clientes.", 500, $e);
        }
    }

    /**
     * Busca um cliente pelo seu ID.
     * @param int $id O ID do cliente.
     * @return Cliente|false
     */
    public function buscar($id)
    {
        try {
            $idParaBuscar = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaBuscar === null) {
                 throw new \InvalidArgumentException("ID para busca não pode ser nulo.");
            }
            $sql = "SELECT * FROM cliente WHERE id = :id";
            $stmt = $this->conexao->prepare($sql); 
            $stmt->bindValue(':id', $idParaBuscar, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Cliente::class); 
        } catch (\PDOException | \InvalidArgumentException $e) { 
            error_log("Erro em ClienteDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar cliente por ID.", 500, $e);
        }
    }
    
    /**
     * Busca um cliente pelo ID do usuário associado.
     * @param int $idUsuario O ID do usuário.
     * @return Cliente|false
     */
    public function buscarPorIdUsuario($idUsuario)
    {
        try {
            $sql = "SELECT * FROM cliente WHERE id_usuario = :id_usuario";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id_usuario', $idUsuario, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Cliente::class);
        } catch (\PDOException $e) {
            error_log("Erro em ClienteDAO->buscarPorIdUsuario: " . $e->getMessage());
            throw new \Exception("Erro ao buscar cliente por ID de usuário.", 500, $e);
        }
    }
}
