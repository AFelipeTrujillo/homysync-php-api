<?php 

use App\Adapters\Persistence\SQLiteUserRepository;
use App\Adapters\Persistence\SQLiteHouseholdRepository;
use App\Services\AuthService;
use App\Adapters\Http\AuthController;
use App\Adapters\Http\AuthMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

// 0. Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 1. Set up the PDO connection
$pdo = new PDO('sqlite:' . __DIR__ . '/../homysync.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 2. Create table users if it doesn't exist
$pdo->exec('
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        name TEXT NOT NULL
    )'
);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS catalog_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        household_id INTEGER NOT NULL,
        canonical_name TEXT NOT NULL,
        category TEXT NOT NULL
    )'
);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS households (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        timezone TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )'
);

$pdo->exec('
    CREATE TABLE IF NOT EXISTS household_members (
        household_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        PRIMARY KEY (household_id, user_id)
    )'
);

// 3. Injection of dependencies and instantiation
$userRepository = new SQLiteUserRepository($pdo);
$householdRepository = new SQLiteHouseholdRepository($pdo);

$authService = new AuthService($userRepository);
$authController = new AuthController($authService);

// 4. Handle the incoming HTTP request
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($requestUri === '/ping' && $requestMethod === 'GET') {
    http_response_code(200);
    echo json_encode(['message' => 'pong']);
    exit;
}

if ($requestUri === '/auth/register' && $requestMethod === 'POST') 
{
    $input = json_decode(file_get_contents('php://input'), true);
    $reponse = $authController->register($input);
    http_response_code($reponse['status']);
    echo json_encode($reponse['data'] ?? ['error' => $reponse['error']]);
    exit;
}

if ($requestUri === '/auth/login' && $requestMethod === 'POST') 
{
    $input = json_decode(file_get_contents('php://input'), true);
    $reponse = $authController->login($input);
    http_response_code($reponse['status']);
    echo json_encode($reponse['data'] ?? ['error' => $reponse['error']]);
    exit;
}

if ($requestUri === '/households' && $requestMethod === 'POST') 
{
    $userData = AuthMiddleware::validateToken($_SERVER['HTTP_AUTHORIZATION']);
    $input = json_decode(file_get_contents('php://input'), true);

    $householdService = new App\Services\HouseholdService($householdRepository);
    $householdController = new App\Adapters\Http\HouseholdController($householdService);
    $response = $householdController->create($userData, $input);
    http_response_code($response['status']);
    echo json_encode($response['data']);
    exit;
}

if ($requestUri === '/households' && $requestMethod === 'PUT') 
{
    $userData = AuthMiddleware::validateToken($_SERVER['HTTP_AUTHORIZATION']);
    $input = json_decode(file_get_contents('php://input'), true);

    $householdService = new App\Services\HouseholdService($householdRepository);
    $householdController = new App\Adapters\Http\HouseholdController($householdService);
    $response = $householdController->update($userData, $input);
    http_response_code($response['status']);
    echo json_encode($response['data']);
    exit;
}

if ($requestUri === '/households' && $requestMethod === 'GET') 
{
    $userData = AuthMiddleware::validateToken($_SERVER['HTTP_AUTHORIZATION']);

    $householdService = new App\Services\HouseholdService($householdRepository);
    $householdController = new App\Adapters\Http\HouseholdController($householdService);
    $response = $householdController->listUserHouseholds($userData);
    http_response_code($response['status']);
    echo json_encode($response['data']);
    exit;
}

http_response_code(404);
echo json_encode([
    "status" => 404,
    "data" => ["error" => "Endpoint not found."]
]);


