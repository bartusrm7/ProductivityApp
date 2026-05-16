<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HabitsController;
use App\Controllers\HabitsDataController;
use App\Controllers\NotesController;
use App\Controllers\TasksController;
use App\Database\Database;
use App\Middlewares\AuthMiddleware;
use App\Repositories\AuthRepository;
use App\Repositories\HabitsDataRepository;
use App\Repositories\HabitsRepository;
use App\Repositories\NotesRepository;
use App\Repositories\TasksRepository;
use App\Services\AuthService;
use App\Services\HabitsDataService;
use App\Services\HabitsService;
use App\Services\JWTService;
use App\Services\NotesService;
use App\Services\TasksService;
use App\Validations\AuthValidation;
use App\Validations\HabitsDataValidation;
use App\Validations\HabitsValidation;
use App\Validations\NotesValidation;
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
$habitsDataRepository = new HabitsDataRepository($db);
$notesRepository = new NotesRepository($db);

// VALIDATIORS
$authValidation = new AuthValidation();
$tasksValidation = new TasksValidation();
$habitsValidation = new HabitsValidation();
$habitsDataValidation = new HabitsDataValidation();
$notesValidation = new NotesValidation();

// SERVICES
$jwtService = new JWTService();
$authService = new AuthService($authRepository, $authValidation);
$tasksService = new TasksService($tasksRepository, $tasksValidation);
$habitsService = new HabitsService($habitsRepository, $habitsValidation);
$habitsDataService = new HabitsDataService($habitsDataRepository, $habitsDataValidation);
$notesService = new NotesService($notesRepository, $notesValidation);

// MIDDLEWARES
$authMiddleware = new AuthMiddleware($jwtService);

// CONTROLLERS
$authController = new AuthController($authService, $jwtService);
$tasksController = new TasksController($tasksService, $jwtService);
$habitsController = new HabitsController($habitsService, $jwtService);
$habitsDataController = new HabitsDataController($habitsDataService, $jwtService);
$notesController = new NotesController($notesService, $jwtService);

$controllers = [
    AuthController::class => $authController,
    TasksController::class => $tasksController,
    HabitsController::class => $habitsController,
    HabitsDataController::class => $habitsDataController,
    NotesController::class => $notesController,
];

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // GET

    // AUTH
    $r->addRoute('GET', '/user-name', [AuthController::class, 'getLoggedUserName']);

    // TASKS
    $r->addRoute('GET', '/todo-tasks', [TasksController::class, 'getToDoTasks']);
    $r->addRoute('GET', '/in-progress-tasks', [TasksController::class, 'getInProgressTasks']);
    $r->addRoute('GET', '/done-tasks', [TasksController::class, 'getDoneTasks']);
    $r->addRoute('GET', '/sort-tasks', [TasksController::class, 'sortTasks']);

    // HABITS
    $r->addRoute('GET', '/get-habits', [HabitsController::class, 'getHabits']);
    $r->addRoute('GET', '/get-started-habits', [HabitsController::class, 'getStartedHabits']);

    // NOTES
    $r->addRoute('GET', '/get-notes', [NotesController::class, 'getNotes']);
    $r->addRoute('GET', '/get-saved-notes', [NotesController::class, 'getSavedToHistoryNotes']);
    $r->addRoute('GET', '/sort-notes', [NotesController::class, 'sortNotes']);

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
    $r->addRoute('POST', '/habit-status-started', [HabitsController::class, 'habitStatusStarted']);

    // HABITS DATA 
    $r->addRoute('POST', '/set-habit-done', [HabitsDataController::class, 'setHabitThisDayDone']);
    $r->addRoute('POST', '/count-current-streak-days', [HabitsDataController::class, 'countCurrentStreakDays']);
    $r->addRoute('POST', '/count-amount-days-done', [HabitsDataController::class, 'countAmountDaysDone']);

    // NOTES
    $r->addRoute('POST', '/create-note', [NotesController::class, 'createNote']);
    $r->addRoute('POST', '/set-important-note', [NotesController::class, 'setImportantNote']);
    $r->addRoute('POST', '/save-note-to-history', [NotesController::class, 'saveNoteIntoHistory']);
    $r->addRoute('POST', '/edit-note', [NotesController::class, 'editNote']);
    $r->addRoute('POST', '/delete-note', [NotesController::class, 'deleteNote']);
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
