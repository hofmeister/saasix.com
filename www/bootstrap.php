<?php

define('BASEPATH', realpath(dirname(__FILE__).'/../'));

require_once '../settings.php';
require_once '../lib/functions.php';
require_once '../lib/DB.php';
require_once '../lib/UI.php';
require_once '../lib/Controller.php';


DB::connect();

$controllerName = safepath($_GET['c']);
$methodName = safepath($_GET['m']);

if (!$controllerName) 
    $controllerName = 'index';

if (!$methodName) 
    $methodName = 'index';

$ctrl = Controller::load($controllerName);

if (!method_exists($ctrl, $methodName)) {
    throw new Exception("Method not found: $controllerName::$methodName");
}

