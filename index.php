<?php
include("vendor/autoload.php");

$config = include("config.php");

// Create Router instance
$router = new \Bramus\Router\Router();

// Her definerer vi router-stier.
$router->get('/', 'Site::frontpage');
$router->get('/posts/', 'Posts::list');
$router->get('/posts/{\d}/', 'Posts::single');
$router->get('/admin/', 'Admin::frontpage');

// Run it!
$router->run();


function view($templateName, $vars=[]) {
    $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
    ));
    
    $template = $twig->loadTemplate($templateName);
    
    echo $template->render($vars);
    die();
}

function redirect($url, $httpCode=301) {
    header("Location: ".$url);
    die();
}

function error404() {
    die("Siden kunne ikke finnes");
}

function db() {
    global $config;
    static $db;
    
    if($db)
        return $db;

    $db = new PDO("mysql:host=".$config['db']['host'].";dbname=".$config['dbname'], $config['user'], $config['password']);    
    $db->exec("SET NAMES utf8");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
}
