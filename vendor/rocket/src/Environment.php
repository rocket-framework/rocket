<?php

namespace Rocket;

use Rocket\Session;

class Environment
{ 	
	private static $instance;
	
	private function __construct(){}
	
	public static function getInstance()
	{
		if (!self::$instance):
			self::$instance = new self;
		endif;
		
		self::$instance->securitySession();
		
		return self::$instance;
	}
	
	
	public function app(array $app)
	{
		$charset  = $app['charset'] ?? '';
		$url      = $app['url'] ?? '';
		$errors   = $app['errors'] ?? '';
		$timezone = $app['timezone'] ?? '';

		//header('Content-Type: text/html; charset='.$charset);
		date_default_timezone_set($timezone);
		if($errors == false):
			error_reporting(E_ALL); 
			ini_set('display_errors', 2); 
			ini_set('log_errors', 2); 
			ini_set('error_log', dirname(__FILE__) . './logs/error_log.txt');
		elseif($errors == true):
			error_reporting(E_ALL);
			ini_set('display_errors',1);
		endif;
			define("URL",  $url);
			define("IMG",  URL.'public/img/');
		return $this;
	} 


	public function db(array $db)
	{
		$drive = $db['drive'] ?? '';
		$host  = $db['host']  ?? '';
		$base  = $db['base']  ?? '';
		$user  = $db['user']  ?? '';
		$pass  = $db['pass']  ?? '';               

		define("DRIVE",     $drive);
		define("HOST",      $host);
		define("DB_NAME",   $base);
		define("USER_NAME", $user);
		define("PASSWORD",  $pass);
		return $this;
	} 
	
	private function securitySession()
	{
		$data = Session::getInstance();

		if (!isset($data->lastsession)):
			$data->lastsession =  date('d/m/Y H:i:s',strtotime('+1minute'));
		else:
			if(date('d/m/Y H:i:s') >= $data->lastsession):
				$data->lastsession =  date('d/m/Y H:i:s',strtotime('+1minute'));
				session_regenerate_id();
			endif;
		endif;
	}
}