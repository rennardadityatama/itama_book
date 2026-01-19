<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/config.php';

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

$controllerObject->$method();
