<?php

namespace App\Models\DAO;

use App\Models\Entidades\Usuario;

class UsuarioDAO extends BaseDAO
{

    public function verificaEmail($email)
    {
        try {
            $sql = "SELECT 1 FROM usuario WHERE email = :email LIMIT 1";
            $stmt = $this->select($sql, [':email' => $email]);
            return (bool) $stmt->fetchColumn();
        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->verificaEmail: " . $e->getMessage());
            throw new \Exception("Erro ao verificar e-mail na base de dados.", 500, $e);
        }
    }

    public function buscarPorEmail($email)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Usuario::class);
        } catch (\PDOException $e) {
            error_log("Erro em UsuarioDAO->buscarPorEmail: " . $e->getMessage());
            throw new \Exception("Erro ao buscar utilizador por email.", 500, $e);
        }
    }
    
    public function salvar(Usuario $usuario)
    {
        try {
            return $this->insert(
                'usuario',
                [
                    'nome'         => $usuario->getNome(),
                    'email'        => $usuario->getEmail(),
                    'senha'        => $usuario->getSenha(),
                    'nivel_acesso' => $usuario->getNivelAcesso()
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro na gravação dos dados do utilizador.", 500, $e);
        }
    }

    public function atualizar(Usuario $usuario)
    {
        try {
            $sql = "UPDATE usuario 
                    SET nome = :nome, email = :email, senha = :senha, nivel_acesso = :nivel_acesso 
                    WHERE id = :id";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $usuario->getId(), \PDO::PARAM_INT); 
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', $usuario->getSenha()); 
            $stmt->bindValue(':nivel_acesso', $usuario->getNivelAcesso()); 

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) { 
            error_log("Erro em UsuarioDAO->atualizar: " . $e->getMessage());
            throw new \Exception("Erro na atualização dos dados do utilizador.", 500, $e);
        }
    }

    public function excluir($id)
    {
        try {
            $idParaExcluir = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaExcluir === null) {
                throw new \InvalidArgumentException("ID para exclusão não pode ser nulo.");
            }
            $sql = "DELETE FROM usuario WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException | \InvalidArgumentException $e) { 
            error_log("Erro em UsuarioDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro na exclusão dos dados do utilizador.", 500, $e);
        }
    }

    /**
     * Lista todos os utilizadores com detalhes adicionais das tabelas cliente e fornecedor.
     * Ideal para painéis administrativos.
     * @return array Retorna uma lista de utilizadores com dados de CPF (se cliente) e CNPJ (se fornecedor).
     * @throws \Exception Se ocorrer um erro durante a consulta.
     */
    public function listarUsuariosDetalhados()
    {
        try {
            $sql = "
                SELECT 
                    u.id,
                    u.nome,
                    u.email,
                    u.nivel_acesso,
                    c.cpf,
                    f.cnpj
                FROM 
                    usuario u
                LEFT JOIN 
                    cliente c ON u.id = c.id_usuario
                LEFT JOIN 
                    fornecedor f ON u.id = f.id_usuario
                ORDER BY 
                    u.nome ASC
            ";
            
            $stmt = $this->select($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->listarUsuariosDetalhados: " . $e->getMessage());
            throw new \Exception("Erro ao listar os detalhes dos utilizadores.", 500, $e);
        }
    }

    public function buscar($id)
    {
        try {
            $idParaBuscar = is_array($id) ? ($id[0] ?? null) : $id;
            if ($idParaBuscar === null) {
                 throw new \InvalidArgumentException("ID para busca não pode ser nulo.");
            }
            $sql = "SELECT * FROM usuario WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $idParaBuscar, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(\App\Models\Entidades\Usuario::class);
        } catch (\PDOException | \InvalidArgumentException $e) { 
            error_log("Erro em UsuarioDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar utilizador por ID.", 500, $e);
        }
    }
}
