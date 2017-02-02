<?php

namespace Rocket\Interfaces;

interface iLogin
{
    public function isAuth($login,$password) : bool;
    public function getLogin($value);
}