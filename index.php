<?php

use FabtoszBlog\Utils\DependencyInjector;
use FabtoszBlog\Utils\Router;
use FabtoszBlog\Utils\Request;
use FabtoszBlog\Utils\Session;
use FabtoszBlog\Utils\Validate;

require_once __DIR__ . '/vendor/autoload.php';

session_start();
$session = new Session();

$di = new DependencyInjector();

$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=fabtosz',
    'root',
    'Kre92Hs('
);

$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
$view = new Twig_Environment($loader);

$validator = new Validate();

$di->set('PDO', $pdo);
$di->set('view', $view);
$di->set('validator', $validator);

$router = new Router($di);
$response = $router->route(new Request($session));
echo $response;