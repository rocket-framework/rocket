<?php

namespace App\Models;

use Rocket\Authentication;

class User extends Authentication
{    
	protected $login    = 'email';
	protected $password = 'password';
}