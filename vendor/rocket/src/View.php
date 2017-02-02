<?php

namespace Rocket;

use \Rocket\Helper;

class View extends Helper
{
    private $file;
	private $data = [];

    public function template($file) 
	{
		$this->file = str_replace('.','/',$file);
        return $this;
    }

    public function addData($key, $value) 
	{
        $this->data[$key] = $value;
        return $this;
    }

    public function getData($value = false) 
	{
        return $this->data[$value] ?? false;
    }
    
    public function render() 
	{
        if (file_exists("./app/Views/".$this->file.".phtml")):
            include_once "./app/Views/".$this->file.".phtml";
        endif;
    }
}