<?php

namespace App\Models\DAO;

use App\Lib\Conexao;

abstract class BaseDAO
{
    protected $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::getConnection();
    }

    public function select($sql, array $params = []) 
    {
        if (!empty($sql)) {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($params); 
            return $stmt; 
        }
        return false; 
    }

    public function insert($table, array $dataToInsert)
    {
        if (empty($table) || empty($dataToInsert)) {
            throw new \InvalidArgumentException("Tabela ou dados para inserção não podem ser vazios.");
        }

        $columns = implode(', ', array_keys($dataToInsert));
        $placeholders = ':' . implode(', :', array_keys($dataToInsert));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($dataToInsert);
            
            // CORREÇÃO CRÍTICA: Retornar o ID do último registro inserido
            return $this->conexao->lastInsertId();

        } catch (\PDOException $e) {
            error_log("Erro em BaseDAO->insert: " . $e->getMessage() . " SQL: " . $sql . " Data: " . json_encode($dataToInsert));
            throw new \Exception("Erro ao inserir dados no banco de dados.", 500, $e);
        }
    }

    public function update($table, $cols, $values) 
    {
        if(!empty($table) && !empty($cols) && !empty($values))
        {
            if ($where) {
                $where = "WHERE $where";
           }
            $stmt = $this->conexao->prepare("UPDATE $table SET $cols $where");
            $stmt->execute($values);

            return $stmt->rowCount();
        }else{
            return false;
        }
    }

    public function delete($table, $where = null) 
    {
        if(!empty($table))
        {
            if ($where) {             
                $where = "WHERE $where";
           }
            $stmt = $this->conexao->prepare("DELETE FROM $table $where");
            $stmt->execute();

            return $stmt->rowCount();
        }else{
            return false;
        }
    }
}