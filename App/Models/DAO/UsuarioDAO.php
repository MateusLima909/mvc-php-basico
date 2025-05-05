<?php

namespace App\Models\DAO;

use App\Models\Entidades\Usuario;

class UsuarioDAO extends BaseDAO
{
    public function verificaEmail($email)
    {
        try {

            $query = $this->select(
                "SELECT * FROM usuario WHERE email = '$email' "
            );

            return $query->fetch();

        }catch (Exception $e){
            throw new \Exception("Erro no acesso aos dados.", 500);
        }
    }

    public  function salvar(Usuario $usuario) {
        try {
            $nome      = $usuario->getNome();
            $email     = $usuario->getEmail();
            return $this->insert(
                'usuario',
                ":nome,:email",
                [
                    ':nome'=>$nome,
                    ':email'=>$email
                ]
            );

        }catch (\Exception $e){
            throw new \Exception("Erro na gravação de dados.", 500);
        }
    }

    public function atualizar(Usuario $usuario)
    {
        try {
            $id    = $usuario->getId();
            $nome  = $usuario->getNome();
            $email = $usuario->getEmail();


            $sql = "UPDATE usuario SET nome = :nome, email = :email WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Erro na atualização de dados.", 500);
        }
    }

    public function excluir($id)
    {
        try {
            $sql = "DELETE FROM usuario WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Erro na exclusão de dados.", 500);
        }
    }

    public function listar()
    {
    try {
        $sql = "SELECT * FROM usuario";
        $query = $this->select($sql);

        return $query->fetchAll(\PDO::FETCH_CLASS, Usuario::class);

    } catch (\Exception $e) {
        throw new \Exception("Erro ao listar usuários: " . $e->getMessage());
    }
    }

    public function buscar($id)
{
    try {
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchObject(\App\Models\Entidades\Usuario::class);
    } catch (\Exception $e) {
        throw new \Exception("Erro ao buscar usuário: " . $e->getMessage());
    }
}
}