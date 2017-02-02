<?php

namespace Rocket;

use \Rocket\Interfaces\iLogin;
use \Rocket\Model;
use \Rocket\Connection;
use \Rocket\View;

abstract class Authentication extends Model implements iLogin
{
    protected $login;
    protected $password;

    public final function isAuth($login, $password): bool
    {
        if(is_string($login) && is_string($password)):
            $query = "SELECT * FROM $this->table WHERE $this->login = :LOGIN AND $this->password = :PASSWORD";
            $pdo   = $this->db->prepare($query);
            try{
                $pdo->bindParam(":LOGIN", $login, \PDO::PARAM_STR);
                $pdo->bindParam(":PASSWORD", $password, \PDO::PARAM_STR);
                $pdo->execute();
                if($pdo->rowCount() > 0):
                    return true;
                else:
                    return false;
                endif;
            }catch(\PDOException $e) {
               $erro = utf8_encode($e->getMessage());
               $v = new View(); 
               $v->template('errors.SQLState')->addData('erro',$erro)->render(); 
               die();
            }
        else:
            return false;
        endif;
    }


    public final function getLogin($value)
    {
        $query = "SELECT * FROM $this->table WHERE $this->login = :LOGIN";
        $pdo   = $this->db->prepare($query);
        try {
            $pdo->bindParam(":LOGIN", $value, \PDO::PARAM_STR);
            $pdo->execute();
            return $pdo->fetch();
        }catch (\PDOException $e) {
           $erro = utf8_encode($e->getMessage());
           $v = new View(); 
           $v->template('errors.SQLState')->addData('erro',$erro)->render(); 
           die();
        }
    }
}