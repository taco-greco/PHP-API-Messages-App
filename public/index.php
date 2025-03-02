<?php
session_start();
use src\Controller\ErrorController;

require '../vendor/autoload.php';

// Chargement automatique des classes
function chargerClasse($classe)
{
    $ds = DIRECTORY_SEPARATOR;
    $dir = $_SERVER["DOCUMENT_ROOT"] . "$ds.."; //remonte d’un cran par rapport à index.php
    $className = str_replace('\\', $ds, $classe);
    $file = "{$dir}{$ds}{$className}.php";
    if (is_readable($file)) require_once $file;
}
spl_autoload_register('chargerClasse');

// Router
$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$uri = substr($uri, strlen($scriptName));
$uri = trim($uri, '/');
$URLS = explode("/", $uri);

$controller = isset($URLS[0]) && $URLS[0] != '' ? ucfirst($URLS[0]) : 'Hobby';
$action = isset($URLS[1]) && $URLS[1] != '' ? $URLS[1] : 'index';

// Extract parameters
$param1 = isset($URLS[2]) && is_numeric($URLS[2]) ? (int)$URLS[2] : null;
$param2 = isset($URLS[3]) && is_numeric($URLS[3]) ? (int)$URLS[3] : null;

try {
    $class = "src\Controller\\" . ucfirst($controller) . "Controller";
    if (class_exists($class)) {
        $controllerInstance = new $class();
        if (method_exists($controllerInstance, $action)) {
            // Pass both parameters to the method
            echo $controllerInstance->$action($param1, $param2);
        } else {
            throw new Exception("Action {$action} does not exist in {$class}");
        }
    } else {
        throw new Exception("Controller {$controller} does not exist");
    }
} catch (Exception $e) {
    $controllerInstance = new ErrorController();
    echo json_encode($controllerInstance->show($e));
}
