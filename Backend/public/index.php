<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HabitsController;
use App\Controllers\TasksController;
use App\Database\Database;
use App\Middlewares\AuthMiddleware;
use App\Repositories\AuthRepository;
use App\Repositories\HabitsRepository;
use App\Repositories\TasksRepository;
use App\Services\AuthService;
use App\Services\HabitsService;
use App\Services\JWTService;
use App\Services\TasksService;
use App\Validations\AuthValidation;
use App\Validations\HabitsValidation;
use App\Validations\TasksValidation;
use Dotenv\Dotenv;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
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
$tasksRepository = new TasksRepository($db);
$habitsRepository = new HabitsRepository($db);

// VALIDATIORS
$authValidation = new AuthValidation();
$tasksValidation = new TasksValidation();
$habitsValidation = new HabitsValidation();

// SERVICES
$jwtService = new JWTService();
$authService = new AuthService($authRepository, $authValidation);
$tasksService = new TasksService($tasksRepository, $tasksValidation);
$habitsService = new HabitsService($habitsRepository, $habitsValidation);

// MIDDLEWARES
$authMiddleware = new AuthMiddleware($jwtService);

// CONTROLLERS
$authController = new AuthController($authService, $jwtService);
$tasksController = new TasksController($tasksService, $jwtService);
$habitsController = new HabitsController($habitsService, $jwtService);

$controllers = [
    AuthController::class => $authController,
    TasksController::class => $tasksController,
    HabitsController::class => $habitsController,
];

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // GET

    // AUTH
    $r->addRoute('GET', '/user-name', [AuthController::class, 'getLoggedUserName']);

    // TASKS
    $r->addRoute('GET', '/todo-tasks', [TasksController::class, 'getToDoTasks']);
    $r->addRoute('GET', '/in-progress-tasks', [TasksController::class, 'getInProgressTasks']);
    $r->addRoute('GET', '/done-tasks', [TasksController::class, 'getDoneTasks']);

    // HABITS
    $r->addRoute('GET', '/get-habits', [HabitsController::class, 'getHabits']);

    // POST

    // AUTH
    $r->addRoute('POST', '/sign-up', [AuthController::class, 'userRegistration']);
    $r->addRoute('POST', '/sign-in', [AuthController::class, 'userLogin']);

    // TASKS
    $r->addRoute('POST', '/create-task', [TasksController::class, 'createNewTask']);
    $r->addRoute('POST', '/done-task', [TasksController::class, 'doneTask']);
    $r->addRoute('POST', '/edit-task', [TasksController::class, 'editTask']);
    $r->addRoute('POST', '/delete-task', [TasksController::class, 'deleteTask']);

    // HABITS
    $r->addRoute('POST', '/create-habit', [HabitsController::class, 'newHabit']);
    $r->addRoute('POST', '/edit-habit', [HabitsController::class, 'editHabit']);
    $r->addRoute('POST', '/delete-habit', [HabitsController::class, 'deleteHabit']);
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

        $protectedRoutes = [
            '/dashboard',
            '/tasks',
            '/habits',
            '/notes',
            '/goals',
            '/settings',
        ];
        if (in_array($uri, $protectedRoutes)) {
            $authMiddleware->userAccess();
        }

        if (is_callable($handler)) {
            return $handler($vars);
        }

        [$class, $method] = $handler;
        $controller = $controllers[$class];
        $controller->$method($vars);
}
