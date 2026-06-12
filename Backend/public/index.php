<?php

declare(strict_types=1);

use App\Controllers\ActiveLogsController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\GoalsController;
use App\Controllers\HabitsController;
use App\Controllers\HabitsDataController;
use App\Controllers\NotesController;
use App\Controllers\SettingsController;
use App\Controllers\TasksController;
use App\Controllers\TasksDataController;
use App\Database\Database;
use App\Middlewares\AuthMiddleware;
use App\Repositories\ActivityLogRepository;
use App\Repositories\AuthRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\GoalsRepository;
use App\Repositories\HabitsDataRepository;
use App\Repositories\HabitsRepository;
use App\Repositories\NotesRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\TasksDataRepository;
use App\Repositories\TasksRepository;
use App\Services\ActiveLogsService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\GoalsService;
use App\Services\HabitsDataService;
use App\Services\HabitsService;
use App\Services\JWTService;
use App\Services\NotesService;
use App\Services\SettingsService;
use App\Services\TasksDataService;
use App\Services\TasksService;
use App\Validations\ActiveLogsValidation;
use App\Validations\AuthValidation;
use App\Validations\DashboardValidation;
use App\Validations\GoalsValidation;
use App\Validations\HabitsDataValidation;
use App\Validations\HabitsValidation;
use App\Validations\NotesValidation;
use App\Validations\SettingsValidation;
use App\Validations\TasksDataValidation;
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
$activeLogsRepository = new ActivityLogRepository($db);
$dashboardRepository = new DashboardRepository($db);
$tasksRepository = new TasksRepository($db);
$tasksDataRepository = new TasksDataRepository($db);
$habitsRepository = new HabitsRepository($db);
$habitsDataRepository = new HabitsDataRepository($db);
$notesRepository = new NotesRepository($db);
$goalsRepository = new GoalsRepository($db);
$settingsRepository = new SettingsRepository($db);

// VALIDATIORS
$authValidation = new AuthValidation();
$activeLogsValidation = new ActiveLogsValidation();
$dashboardValidation = new DashboardValidation();
$tasksValidation = new TasksValidation();
$tasksDataValidation = new TasksDataValidation();
$habitsValidation = new HabitsValidation();
$habitsDataValidation = new HabitsDataValidation();
$notesValidation = new NotesValidation();
$goalsValidation = new GoalsValidation();
$settingsValidation = new SettingsValidation();

// SERVICES
$jwtService = new JWTService();
$authService = new AuthService($authRepository, $authValidation);
$activeLogsService = new ActiveLogsService($activeLogsRepository, $activeLogsValidation);
$dashboardService = new DashboardService($dashboardRepository, $dashboardValidation);
$tasksService = new TasksService($tasksRepository, $tasksDataRepository, $activeLogsRepository, $tasksValidation);
$tasksDataService = new TasksDataService($tasksDataRepository, $activeLogsRepository, $tasksDataValidation);
$habitsService = new HabitsService($habitsRepository, $activeLogsRepository, $habitsValidation);
$habitsDataService = new HabitsDataService($habitsDataRepository, $activeLogsRepository, $habitsDataValidation);
$notesService = new NotesService($notesRepository, $activeLogsRepository, $notesValidation);
$goalsService = new GoalsService($goalsRepository, $activeLogsRepository, $goalsValidation);
$settingsService = new SettingsService($settingsRepository, $activeLogsRepository, $settingsValidation);

// MIDDLEWARES
$authMiddleware = new AuthMiddleware($jwtService);

// CONTROLLERS
$authController = new AuthController($authService, $jwtService);
$activeLogsController = new ActiveLogsController($activeLogsService, $jwtService);
$dashboardController = new DashboardController($dashboardService, $jwtService);
$tasksController = new TasksController($tasksService, $jwtService);
$tasksDataController = new TasksDataController($tasksDataService);
$habitsController = new HabitsController($habitsService, $jwtService);
$habitsDataController = new HabitsDataController($habitsDataService, $jwtService);
$notesController = new NotesController($notesService, $jwtService);
$goalsController = new GoalsController($goalsService, $jwtService);
$settingsController = new SettingsController($settingsService, $jwtService);

$controllers = [
    AuthController::class => $authController,
    ActiveLogsController::class => $activeLogsController,
    DashboardController::class => $dashboardController,
    TasksController::class => $tasksController,
    TasksDataController::class => $tasksDataController,
    HabitsController::class => $habitsController,
    HabitsDataController::class => $habitsDataController,
    NotesController::class => $notesController,
    GoalsController::class => $goalsController,
    SettingsController::class => $settingsController,
];

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // GET

    // AUTH
    $r->addRoute('GET', '/user-name', [AuthController::class, 'getName']);
    $r->addRoute('GET', '/user-email', [AuthController::class, 'getLoggedUserEmail']);
    $r->addRoute('GET', '/get-user-avatar', [AuthController::class, 'getAvatar']);

    // ACTIVE LOGS
    $r->addRoute('GET', '/get-no-readed-logs', [ActiveLogsController::class, 'getNoReadedLogs']);

    // DASHBOARD
    $r->addRoute('GET', '/get-all-logs', [DashboardController::class, 'getAllLogs']);

    // TASKS
    $r->addRoute('GET', '/todo-tasks', [TasksController::class, 'getToDoTasks']);
    $r->addRoute('GET', '/in-progress-tasks', [TasksController::class, 'getInProgressTasks']);
    $r->addRoute('GET', '/done-tasks', [TasksController::class, 'getDoneTasks']);
    $r->addRoute('GET', '/failed-tasks', [TasksController::class, 'getFailedTasks']);
    $r->addRoute('GET', '/sort-tasks', [TasksController::class, 'sortTasks']);
    $r->addRoute('GET', '/get-today-tasks', [TasksController::class, 'getTodayTasks']);
    $r->addRoute('GET', '/get-all-tasks', [TasksController::class, 'getAllTasks']);

    // HABITS
    $r->addRoute('GET', '/get-habits', [HabitsController::class, 'getHabits']);
    $r->addRoute('GET', '/get-started-habits', [HabitsController::class, 'getStartedHabits']);
    $r->addRoute('GET', '/sort-habits', [HabitsController::class, 'sortHabits']);
    $r->addRoute('GET', '/get-current-habits-streak', [HabitsController::class, 'getCurrentHabitStreaks']);
    $r->addRoute('GET', '/get-best-habits-streak', [HabitsController::class, 'getBestHabitStreaks']);

    // NOTES
    $r->addRoute('GET', '/get-notes', [NotesController::class, 'getNotes']);
    $r->addRoute('GET', '/get-saved-notes', [NotesController::class, 'getSavedToHistoryNotes']);
    $r->addRoute('GET', '/sort-notes', [NotesController::class, 'sortNotes']);

    // GOALS
    $r->addRoute('GET', '/get-goals-in-progress', [GoalsController::class, 'getGoalsInProgress']);
    $r->addRoute('GET', '/get-goals-done', [GoalsController::class, 'getGoalsDone']);
    $r->addRoute('GET', '/sort-goals', [GoalsController::class, 'sortGoals']);

    // SETTINGS
    $r->addRoute('GET', '/user-avatar', [SettingsController::class, 'displayUserAvatar']);
    $r->addRoute('GET', '/reminders-state', [SettingsController::class, 'getRemindersState']);

    // POST

    // AUTH
    $r->addRoute('POST', '/sign-up', [AuthController::class, 'userRegistration']);
    $r->addRoute('POST', '/sign-in', [AuthController::class, 'userLogin']);

    // ACTIVE LOGS
    $r->addRoute('POST', '/readed-notification', [ActiveLogsController::class, 'setNotificationAsReaded']);

    // TASKS
    $r->addRoute('POST', '/create-task', [TasksController::class, 'createNewTask']);
    $r->addRoute('POST', '/done-task', [TasksController::class, 'doneTask']);
    $r->addRoute('POST', '/edit-task', [TasksController::class, 'editTask']);
    $r->addRoute('POST', '/delete-task', [TasksController::class, 'deleteTask']);
    $r->addRoute('POST', '/failed-task', [TasksController::class, 'taskFailed']);

    // TASKS DATA
    $r->addRoute('POST', '/set-deadline-day', [TasksDataController::class, 'setDeadline']);

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

    // GOALS
    $r->addRoute('POST', '/create-goal', [GoalsController::class, 'createGoal']);
    $r->addRoute('POST', '/done-goal', [GoalsController::class, 'doneGoal']);
    $r->addRoute('POST', '/edit-goal', [GoalsController::class, 'editGoal']);
    $r->addRoute('POST', '/delete-goal', [GoalsController::class, 'deleteGoal']);

    // SETTINGS
    $r->addRoute('POST', '/set-avatar', [SettingsController::class, 'updateAvatar']);
    $r->addRoute('POST', '/update-user-name', [SettingsController::class, 'updateUserName']);
    $r->addRoute('POST', '/update-reminders', [SettingsController::class, 'setReminders']);
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
