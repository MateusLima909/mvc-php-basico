<?php

namespace App\Models\DAO;

use App\Models\Entidades\Usuario;
// Removido: use Exception; // Não é necessário se usar \Exception

class UsuarioDAO extends BaseDAO
{
    public function verificaEmail($email)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            // Supondo que $this->select() do BaseDAO foi corrigido para usar prepare/execute com params
            $stmt = $this->select($sql, [':email' => $email]);
            return $stmt->fetch();
        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->verificaEmail: " . $e->getMessage());
            throw new \Exception("Erro ao verificar e-mail no banco de dados.", 500, $e);
        }
    }

    public function buscarPorEmail($email)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':email', $email); // PDO geralmente infere string, mas pode ser explícito se necessário
            $stmt->execute();

            return $stmt->fetchObject(\App\Models\Entidades\Usuario::class);
        } catch (\PDOException $e) { // Mais específico para erros de banco
            error_log("Erro em UsuarioDAO->buscarPorEmail: " . $e->getMessage());
            throw new \Exception("Erro ao buscar usuário por email.", 500, $e);
        }
    }
    
    public function salvar(Usuario $usuario)
    {
        try {
            // Supondo que $this->insert() do BaseDAO foi corrigido
            return $this->insert(
                'usuario',
                [
                    'nome'          => $usuario->getNome(),
                    'email'         => $usuario->getEmail(),
                    'senha'         => $usuario->getSenha(), // Lembre-se de hashear ANTES de chamar salvar
                    'nivel_acesso'  => $usuario->getNivelAcesso()
                ]
            );
        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->salvar: " . $e->getMessage());
            throw new \Exception("Erro na gravação dos dados do usuário.", 500, $e);
        }
    }

    public function atualizar(Usuario $usuario)
    {
        try {
            // No seu código original o placeholder era :nivel para nivel_acesso.
            // Vou manter :nivel, mas se a coluna for nivel_acesso, o ideal é usar :nivel_acesso
            $sql = "UPDATE usuario 
                    SET nome = :nome, email = :email, senha = :senha, nivel_acesso = :nivel_param 
                    WHERE id = :id"; // Renomeei :nivel para :nivel_param para evitar confusão com a coluna
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $usuario->getId(), \PDO::PARAM_INT); // Adicionado PARAM_INT
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', $usuario->getSenha()); // Lembre-se de hashear se a senha for alterada
            $stmt->bindValue(':nivel_param', $usuario->getNivelAcesso()); // Usando :nivel_param

            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) { // Mais específico
            error_log("Erro em UsuarioDAO->atualizar: " . $e->getMessage());
            throw new \Exception("Erro na atualização dos dados do usuário.", 500, $e);
        }
    }

    public function excluir($id)
    {
        try {
            // Garante que $id é um escalar, se for um array pega o primeiro elemento.
            // Se o controller já garante que $id é escalar, pode simplificar.
            $idParaExcluir = is_array($id) ? ($id[0] ?? null) : $id;

            if ($idParaExcluir === null) {
                throw new \InvalidArgumentException("ID para exclusão não pode ser nulo.");
            }

            $sql = "DELETE FROM usuario WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            // CORREÇÃO PRINCIPAL: Adicionar \PDO::PARAM_INT
            $stmt->bindValue(':id', $idParaExcluir, \PDO::PARAM_INT); 
            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $e) { // Mais específico
            error_log("Erro em UsuarioDAO->excluir: " . $e->getMessage());
            throw new \Exception("Erro na exclusão dos dados do usuário.", 500, $e);
        } catch (\InvalidArgumentException $e) { // Captura o erro de ID nulo
             error_log("Erro em UsuarioDAO->excluir: " . $e->getMessage());
            throw $e; // Relança a exceção para ser tratada pelo controller
        }
    }

    public function listar()
    {
        try {
            $sql = "SELECT * FROM usuario";
            // Supondo que $this->select() do BaseDAO foi corrigido e retorna PDOStatement
            $stmt = $this->select($sql); 
            return $stmt->fetchAll(\PDO::FETCH_CLASS, \App\Models\Entidades\Usuario::class);
        } catch (\Exception $e) {
            error_log("Erro em UsuarioDAO->listar: " . $e->getMessage());
            throw new \Exception("Erro ao listar usuários.", 500, $e);
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
        } catch (\PDOException $e) { // Mais específico
            error_log("Erro em UsuarioDAO->buscar: " . $e->getMessage());
            throw new \Exception("Erro ao buscar usuário por ID.", 500, $e);
        } catch (\InvalidArgumentException $e) {
            error_log("Erro em UsuarioDAO->buscar: " . $e->getMessage());
            throw $e;
        }
    }
}
