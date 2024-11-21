<?php
// Enable error reporting
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the base path for includes
define('BASE_PATH', __DIR__ . '/');

// Include the configuration file
require_once BASE_PATH . 'config.php';

// Include necessary files
require_once BASE_PATH . 'src/Database.php';
require_once BASE_PATH . 'src/RecipeManager.php';
require_once BASE_PATH . 'src/Recipe.php';

// Create an instance of recipeManager
$recipeManager = new RecipeManager();

// Get the action from the URL, default to 'list' if not set
$action = $_GET['action'] ?? 'list';

// Handle different actions
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipeManager->createRecipe ($_POST['user_id'],
            $_POST['title'],
            $_POST['description'],
            $_POST['prep_time'],
            $_POST['steps']);
            header('Location: ' . BASE_URL);
            exit;
        }
        require BASE_PATH . 'views/task_form.php';
        break;
    /*case 'toggle':
        $recipeManager->toggleTask($_GET['id']);
        header('Location: ' . BASE_URL);
        break;*/
    case 'delete':
        $recipeManager->deleteRecipe($_GET['id']);
        header('Location: ' . BASE_URL);
        break;
    default:
        $recipes = $recipeManager->getAllRecipes();
        require BASE_PATH . 'views/task_list.php';
        break;
}
