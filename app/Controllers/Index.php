<?php

namespace App\Controllers;

use \Rocket\View;

class Index
{    
    public function index() 
    {
        $v = new View();
        $v->template('Welcome')->addData('title','Bem-Vindo')->render();
    }
}