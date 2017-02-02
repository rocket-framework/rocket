<?php

namespace Rocket;

use \PDO;

class Connection
{
    private static $con;

    public static function connect()
    {
        if(!self::$con){
            self::open();
        }
        return self::$con;
    }

    public static function open()
    {
        try {
            self::$con = new PDO(DRIVE.':host='.HOST.';dbname='.DB_NAME ,USER_NAME,PASSWORD);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            if(DRIVE == 'mysql'):
                self::$con->exec("SET NAMES 'utf8'");
            endif;
        }catch (\PDOException $e){
            $erro = utf8_encode($e->getMessage());
            $v = new View();
            $v->template('errors.SQLState')->addData('erro',$erro)->render();
            die();
		}
    }
}