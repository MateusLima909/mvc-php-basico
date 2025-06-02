<?php

namespace App\Models\DAO;

use App\Models\Entidades\Cliente; // Certifique-se que o caminho e nome da entidade estão corretos

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
            // Seleciona 1 para eficiência, só precisamos saber se existe ou não
            $sql = "SELECT 1 FROM cliente WHERE cpf = :cpf LIMIT 1";
            
            // Supondo que $this->select() do BaseDAO foi corrigido para usar prepare/execute com params
            // e retorna um PDOStatement
            $stmt = $this->select($sql, [':cpf' => $cpf]);
            
            // fetchColumn() retorna o valor da primeira coluna da próxima linha ou false se não houver mais linhas.
            return (bool) $stmt->fetchColumn(); 
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->verificaCpf: " . $e->getMessage());
            throw new \Exception("Erro ao verificar CPF no banco de dados.", 500, $e);
        }
    }

    /**
     * Salva um novo cliente no banco de dados.
     * @param Cliente $cliente O objeto Cliente a ser salvo.
     * @return int O número de linhas afetadas.
     * @throws \Exception Se ocorrer um erro durante a gravação.
     */
    public function salvar(Cliente $cliente)
    {
        try {
            // Supondo que $this->insert() do BaseDAO foi corrigido e espera um array associativo ['coluna' => 'valor']
            return $this->insert(
                'cliente', // Nome da tabela
                [
                    // As chaves são os nomes das colunas REAIS no banco
                    'nome' => $cliente->getNome(),
                    'dtnasc' => $cliente->getDtnasc(), // Formato YYYY-MM-DD esperado pelo MySQL para DATE
                    'cpf' => $cliente->getCpf(),
                    'telefone' => $cliente->getTelefone()
                    // Se você adicionou a coluna id_usuario na tabela cliente e quer gerenciá-la aqui:
                    // 'id_usuario' => $cliente->getIdUsuario() // Certifique-se que getIdUsuario() existe e retorna o valor correto
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro ao gravar os dados do cliente: " . $e->getMessage(), 500, $e);
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
                        telefone = :telefone
                        /* Se for atualizar id_usuario: */
                        /* , id_usuario = :id_usuario */
                    WHERE id = :id";
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindValue(':id', $cliente->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':nome', $cliente->getNome());
            $stmt->bindValue(':dtnasc', $cliente->getDtnasc()); // Formato YYYY-MM-DD
            $stmt->bindValue(':cpf', $cliente->getCpf());
            $stmt->bindValue(':telefone', $cliente->getTelefone());
            // Se for atualizar id_usuario:
            // if ($cliente->getIdUsuario() !== null) {
            //     $stmt->bindValue(':id_usuario', $cliente->getIdUsuario(), \PDO::PARAM_INT);
            // } else {
            //     $stmt->bindValue(':id_usuario', null, \PDO::PARAM_NULL);
            // }

            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) { // Mais específico para erros de banco
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
                throw new \InvalidArgumentException("ID para exclusão do cliente não pode ser nulo.");
            }

            $sql = "DELETE FROM cliente WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) {
            error_log("Erro em ClienteDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro ao excluir o cliente.", 500, $e);
        } catch (\InvalidArgumentException $e) {
             error_log("Erro em ClienteDAO->excluir: " . $e->getMessage());
            throw $e; 
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
            // Supondo que $this->select() do BaseDAO foi corrigido e retorna PDOStatement
            $stmt = $this->select($sql); 
            return $stmt->fetchAll(\PDO::FETCH_CLASS, \App\Models\Entidades\Cliente::class);
        } catch (\Exception $e) {
            error_log("Erro em ClienteDAO->listar: " . $e->getMessage());
            throw new \Exception("Erro ao listar os clientes.", 500, $e);
        }
    }

    /**
     * Busca um cliente pelo ID.
     * @param int $id O ID do cliente a ser buscado.
     * @return Cliente|false Um objeto Cliente se encontrado, false caso contrário.
     * @throws \Exception Se ocorrer um erro durante a busca.
     */
    public function buscar($id)
    {
        try {
            $idParaBuscar = is_array($id) ? ($id[0] ?? null) : $id;

            if ($idParaBuscar === null) {
                 throw new \InvalidArgumentException("ID para busca do cliente não pode ser nulo.");
            }

            $sql = "SELECT * FROM cliente WHERE id = :id";
            $stmt = $this->conexao->prepare($sql); 
            $stmt->bindValue(':id', $idParaBuscar, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchObject(\App\Models\Entidades\Cliente::class); 
        } catch (\PDOException $e) { 
            error_log("Erro em ClienteDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar cliente por ID.", 500, $e);
        } catch (\InvalidArgumentException $e) {
            error_log("Erro em ClienteDAO->buscar: " . $e->getMessage());
            throw $e;
        }
    }
}
