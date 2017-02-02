<?php

namespace Rocket;

use \Rocket\Interfaces\iCrud;
use \Rocket\AttributesCreate;
use \Rocket\AttributesUpdate;
use \Rocket\Connection;
use \Rocket\View;
use \PDO;

abstract class Model implements iCrud
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db    = Connection::connect();
        $this->table = $this->class_to_table();
    }
    
    public final function class_to_table()
    {
        if($pos = strrpos(get_class($this),'\\')):
            return strtolower(substr(get_class($this), $pos + 1));
            return strtolower($pos);
        endif;
    }

    public final function create($attributes): bool
    {
        $attributesCreate = new AttributesCreate();

        $fields = $attributesCreate->createFields($attributes);
        $values = $attributesCreate->createValues($attributes);

        $query  = "INSERT INTO $this->table ($fields) VALUES ($values)";
        $pdo = $this->db->prepare($query);
        $bindParameters = $attributesCreate->bindCreateParameters($attributes);
        try {
            $pdo->execute($bindParameters);
            if($pdo->rowCount() > 0):
                return true;
            else:
                return false;
            endif;
        }catch (PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View();
           $v->template('errors.SQLState')->addData('erro',$erro)->render();
           die();
        }
    }


    public final function read($order_by = null)
    {
        if($order_by != null):
            $query = "SELECT * FROM $this->table ORDER BY $order_by";
        else:
            $query = "SELECT * FROM $this->table";
        endif;
            $pdo = $this->db->prepare($query);
        try {
            $pdo->execute();
            return $pdo->fetchAll();
        }catch (\PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View();
           $v->template('errors.SQLState')->addData('erro',$erro)->render();
           die();
        }
    }

    public final function update($attributes, $id): bool
    {
        $attributesUpdate = new AttributesUpdate();
        $fields = $attributesUpdate->updateFields($attributes);
        $query = "UPDATE $this->table SET $fields WHERE id = :id";
        $pdo = $this->db->prepare($query);
        $bindUpdateParameters = $attributesUpdate->bindUpdateParameters($attributes);
        $bindUpdateParameters['id'] = $id;
        try {
            $pdo->execute($bindUpdateParameters);
            if($pdo->rowCount() > 0):
                return true;
            else:
                return false;
            endif;
        }catch (\PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View();
           $v->template('errors.SQLState')->addData('erro',$erro)->render();
           die();
        }
    }

    public final function delete($column, $value): bool
    {
        if(is_string($column)):
            $query = "DELETE FROM $this->table WHERE $column = :value";
            $pdo   = $this->db->prepare($query);
            $pdo->bindParam(':value', $value, PDO::PARAM_STR);
            try {
                $pdo->execute();
                if($pdo->rowCount() > 0):
                    return true;
                else:
                    return false;
                endif;
            }catch (\PDOException $e) {
               $erro = utf8_encode($e->getMessage());
               $v = new View();
               $v->template('errors.SQLState')->addData('erro',$erro)->render();
               die();
            }
        else:
            return false;
        endif;
    }

    public final function find($column, $value)
    {
        $column = trim($column);
        if(is_string($column)):
            $column = trim($column);
            $query = "SELECT * FROM $this->table WHERE $column = :value";
            $pdo   = $this->db->prepare($query);
            $pdo->bindParam(':value', $value, PDO::PARAM_STR);
            try {
                $pdo->execute();
                return $pdo->fetch();
            }catch (\PDOException $e) {
                $erro = utf8_encode($e->getMessage());
                $v = new View();
                $v->template('errors.SQLState')->addData('erro',$erro)->render();
                die();
            }
        endif;
    }

    public final function like($column, $value)
    {
        $column = trim($column);
        if(is_string($column)):
            $query = "SELECT * FROM $this->table WHERE $column LIKE '%$value%'";
            $pdo = $this->db->prepare($query);
            try {
                $pdo->execute();
                return $pdo->fetchAll();
            } catch (\PDOException $e) {
               $erro = utf8_encode($e->getMessage());
               $v = new View();
               $v->template('errors.SQLState')->addData('erro',$erro)->render();
               die();
            }
        endif;
    }

    public final function where($column, $condition, $value)
    {
        if(is_string($column) && is_string($condition)):
            $query = "SELECT * FROM $this->table WHERE $column $condition :value";
            $pdo = $this->db->prepare($query);
            $pdo->bindParam(':value', $value, PDO::PARAM_STR);
            try {
                $pdo->execute();
                return $pdo->fetchAll();
            } catch (\PDOException $e) {
               $erro = utf8_encode($e->getMessage());
               $v = new View();
               $v->template('errors.SQLState')->addData('erro',$erro)->render();
               die();
            }
        endif;
    }

    public final function count(): int
    {
        $query = "SELECT * FROM $this->table";
        $pdo   = $this->db->prepare($query);
        try {
            $pdo->execute();
            return $pdo->rowCount();
        }catch (\PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View();
           $v->template('errors.SQLState')->addData('erro',$erro)->render();
           die();
        }
    }

    public final function query($param)
    {
        $query = $param;
        $pdo   = $this->db->prepare($query);
        try {
            $pdo->execute();
            return $pdo->fetchAll();
        }catch (\PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View();
           $v->template('errors.SQLState')->addData('erro',$erro)->render();
           die();
        }
    }

    public final function  read_page($init ,$results, $order_by = null)
    {
        if(preg_match("/^[0-9]$/",$init) && preg_match("/^[0-9]$/",$results)):
            if($order_by != null):
                $query = "SELECT * FROM $this->table ORDER BY $order_by LIMIT :init , :results";
            else:
                $query = "SELECT * FROM $this->table LIMIT $init , $results";
            endif;

            $pdo = $this->db->prepare($query);
            $pdo->bindParam(':init', $init, PDO::PARAM_INT);
            $pdo->bindParam(':results', $results, PDO::PARAM_INT);
            try {
                $pdo->execute();
                return $pdo->fetchAll();
            }catch (\PDOException $e) {
                $erro = utf8_encode($e->getMessage());
                $v = new View();
                $v->template('errors.SQLState')->addData('erro',$erro)->render();
               die();
            }
        endif;
    }
}