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
            return false;
        }

        $columns = implode(', ', array_keys($dataToInsert));
        // Cria placeholders como :coluna1, :coluna2 (ex: :nome, :email)
        $placeholders = ':' . implode(', :', array_keys($dataToInsert));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
         $stmt = $this->conexao->prepare($sql);
         $stmt->execute($dataToInsert); // PDO mapeia as chaves do array para os placeholders
         return $stmt->rowCount();
        } catch (\PDOException $e) {
            // error_log("Erro no insert: " . $e->getMessage());
            throw $e;
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