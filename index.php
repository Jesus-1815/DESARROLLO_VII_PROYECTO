<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the base path for includes
define('BASE_PATH', __DIR__ . '/');

// Include the configuration file
require_once BASE_PATH . 'config.php';

// Include necessary files
require_once BASE_PATH . 'src/Database.php';
require_once BASE_PATH . 'src/TaskManager.php';
require_once BASE_PATH . 'src/Task.php';

// Create an instance of TaskManager
$taskManager = new TaskManager();

// Get the action from the URL, default to 'list' if not set
$action = $_GET['action'] ?? 'list';

// Handle different actions
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskManager->createTask($_POST['title']);
            header('Location: ' . BASE_URL);
            exit;
        }
        require BASE_PATH . 'views/task_form.php';
        break;
    case 'toggle':
        $taskManager->toggleTask($_GET['id']);
        header('Location: ' . BASE_URL);
        break;
    case 'delete':
        $taskManager->deleteTask($_GET['id']);
        header('Location: ' . BASE_URL);
        break;
    default:
        $tasks = $taskManager->getAllTasks();
        require BASE_PATH . 'views/task_list.php';
        break;
}