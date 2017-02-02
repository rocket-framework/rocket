<?php

require_once __DIR__ .'/vendor/autoload.php';

$env = \Rocket\Environment::getInstance();

$env->app(array(
    'url'      => 'http://app.rocket.dev/',
    'timezone' => 'America/Sao_Paulo',
    'errors'   => false
));

$env->db(array(
	'drive' => 'mysql',
    'host'  => 'localhost',
    'base'  => 'rocket',
    'user'  => 'root',
	'pass'  => 'rocket'
));

$app = new \Rocket\App();
$app->run();