<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluye archivos necesarios
require_once 'config.php';
require_once 'src/Database.php';
require_once 'src/RecipeManager.php';
require_once 'src/Recipe.php';  // Asegúrate de incluir la clase Recipe

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Instancia del RecipeManager
$recipeManager = new RecipeManager();

// Manejo de acciones desde la URL
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        // Redirige al formulario de nueva receta
        require 'views/task_form.php';
        break;

    case 'store':
        // Procesa el formulario para guardar una nueva receta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; // El ID del usuario en sesión
    $title = $_POST['recipe_name'];
    $description = $_POST['description'];
    $prepTime = $_POST['prep_time'];
    $steps = $_POST['steps']; // Pasos de la receta enviados desde el formulario

    // Guarda la receta
    $recipeId = $recipeManager->createRecipe($userId, $title, $description, $prepTime, $steps);

    // Guarda los ingredientes asociados (si los hay)
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];
    $ingredients = $_POST['ingredient'];

    foreach ($ingredients as $index => $ingredientName) {
        $quantity = $quantities[$index];
        $unit = $units[$index];
        $recipeManager->addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit);
    }

    // Redirige al índice después de guardar
    header("Location: index.php");
    exit;
}
        break;

    case 'delete':
        // Elimina una receta
        if (isset($_GET['id'])) {
            $recipeManager->deleteRecipe($_GET['id']);
        }
        header("Location: index.php");
        break;

    default:
        // Muestra la lista de recetas
        $recipes = $recipeManager->getAllRecipes();
        require 'views/task_list.php';
        break;
}