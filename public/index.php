<?php
session_start();
use src\Controller\ErrorController;

require '../vendor/autoload.php';
// Chargement automatique des classes
function chargerClasse($classe)
{
    $ds = DIRECTORY_SEPARATOR;
    $dir = $_SERVER["DOCUMENT_ROOT"] . "$ds.."; //remonte d’un cran par rapport à index.php
    // Remplacement des séparateur Namespace
    $className = str_replace('\\', $ds, $classe);
    $file = "{$dir}{$ds}{$className}.php";
    if (is_readable($file)) require_once $file;
}
// enregistrement de la fonction "chargerClasse" sur une instanciation de classe
spl_autoload_register('chargerClasse');

// Router
$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$uri = substr($uri, strlen($scriptName));
$uri = trim($uri, '/');
$URLS = explode("/", $uri);

$controller = (isset($URLS[0]) && $URLS[0] != '') ? $URLS[0] : 'Hobby';
$action = (isset($URLS[1]) && $URLS[1] != '') ? $URLS[1] : 'index';
$param = (isset($URLS[2]) && is_numeric($URLS[2])) ? (int)$URLS[2] : null;

if ($controller != '') {
    try {
        $class = "src\Controller\\" . $controller . "Controller";
        if (class_exists($class)) {
            $controller = new $class();
            if (method_exists($class, $action)) {
                echo $controller->$action($param);
            } else {
                throw new Exception("Action {$action} does not exist in {$class}");
            }
        } else {
            throw new Exception("Controller {$controller} does not exist");
        }
    } catch (Exception $e) {
        //Erreur classe avec Controller
        $controller = new ErrorController;
        echo json_encode($controller->show($e));
    }
} else {
    //Route par défaut (/)
    $controller = new \src\Controller\UserController();
    echo json_encode($controller->login());
}