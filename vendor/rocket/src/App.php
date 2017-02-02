<?php

namespace Rocket;

use \Rocket\View;
use \Rocket\Session;
use \Rocket\Interfaces\iRestrictedAccess;

class App
{
    private $to;
    private $method = "index";
    private $params;           

    public function __construct()
    {
        $url = $_GET['url'] ?? false;
        $url = rtrim($url, "/");

        if ($url):
            $arr = explode("/", $url);

            if (isset($arr[0])):
                $to = strtolower($arr[0]);
                $to = explode("-", $to);
                $strTo = '';
                foreach ($to as $k => $v):
                    $strTo.= strtoupper(substr($v, 0, 1)) . substr($v, 1);
                endforeach;
                $this->to = "\\App\\Controllers\\".$strTo;
            endif;

            if (isset($arr[1])):
                $mt = strtolower($arr[1]);
                $mt = explode("-", $mt);
                $strMt = '';
                foreach ($mt as $k => $v):
                    if ($k === 0):
                        $strMt.= $v;
                    else:
                        $strMt.= strtoupper(substr($v, 0, 1)) . substr($v, 1);
                    endif;
                endforeach;
                $this->method = $strMt;
            endif;

            unset($arr[0]);
            unset($arr[1]);
		
			$this->params = array_values($arr);
        else:
            $this->to = "\\App\\Controllers\\Index";
        endif;
    }

    public function run()
    {
        if (class_exists($this->to)):
            $c = new $this->to();

            /*--Implementação de autenticação ---------------------------------*/
            if ($c instanceof iRestrictedAccess):
                $data = Session::getInstance();
                if (!$data->id) header("location: " . URL . "login");
            endif;
            /*--END------------------------------------------------------------*/

            if (method_exists($c, $this->method)):
                if ($this->params !== null):
                   $c->{ $this->method }($this->params);
                else:
				   $c->{ $this->method }();
                endif;
            else:
                $v = new View(); 
                $v->template('errors.PageNotFound')->render();
            endif;
        else:
            $v = new View(); 
            $v->template('errors.PageNotFound')->render();
        endif;
    }

}