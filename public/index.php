<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../config/config.php';
require_once '../app/helpers/mailer.php';
require_once '../app/helpers/role.php';
require_once BASE_PATH . '/vendor/autoload.php';

// Controller & method
$controller = $_GET['c'] ?? 'auth';
$method = $_GET['m'] ?? 'login';

// Format nama controller
$controllerName = ucfirst($controller) . 'Controller';

// Path controller
$controllerFile = "../app/controllers/$controllerName.php";

if (!file_exists($controllerFile)) {
  die("Controller $controllerName tidak ditemukan");
}

require_once $controllerFile;

$controllerObject = new $controllerName;

if (!method_exists($controllerObject, $method)) {
  die("Method $method tidak ditemukan");
}

// ambil parameter selain c & m
$params = $_GET;
unset($params['c'], $params['m']);

call_user_func_array(
    [$controllerObject, $method],
    array_values($params)
);
