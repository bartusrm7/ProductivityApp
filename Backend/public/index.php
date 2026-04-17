<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Database\Database;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use App\Validations\AuthValidation;
use Dotenv\Dotenv;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Database();
$db->getConnection();

// REPOSITORIES
$authRepository = new AuthRepository($db);

// VALIDATIORS
$authValidation = new AuthValidation();

// SERVICES
$authService = new AuthService($authRepository, $authValidation);

// CONTROLLERS
$authController = new AuthController($authService);

$controllers = [
    AuthController::class => $authController
];


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/sign-up', [AuthController::class, 'userRegistration']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if (is_callable($handler)) {
            return $handler($vars);
        }
        [$class, $method] = $handler;
        $controller = $controllers[$class];
        $controller->$method($vars);
}
