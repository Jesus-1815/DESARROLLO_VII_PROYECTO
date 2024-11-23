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
$action = $_GET['action'] ?? 'list';  // Por defecto, se listan las recetas

switch ($action) {
    case 'create':
        // Redirige al formulario para crear una nueva receta
        require 'views/task_form.php';
        break;

    case 'store':
        // Procesa el formulario para guardar una nueva receta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id']; // El ID del usuario en sesión
            $title = $_POST['recipe_name'];
            $description = $_POST['description'];
            $prepTime = $_POST['prep_time'];
            $ingredients = $_POST['ingredient'];
            $steps = $_POST['steps']; // Pasos de la receta enviados desde el formulario

            // Guarda la receta en la base de datos
            $recipeManager->createRecipe($userId, $title, $description, $prepTime, $ingredients, $steps);
            // Guarda los ingredientes asociados a la receta (si los hay)
            $quantities = $_POST['quantity'];
            $units = $_POST['unit'];
            $ingredients = $_POST['ingredient'];

            // Inserta cada ingrediente en la receta
            foreach ($ingredients as $index => $ingredientName) {
                $quantity = $quantities[$index];
                $unit = $units[$index];
                $recipeManager->addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit);
            }

            // Redirige al índice (lista de recetas) después de guardar
            header("Location: index.php");
            exit;
        }
        break;

    case 'delete':
        // Elimina una receta
        if (isset($_GET['id'])) {
            $recipeManager->deleteRecipe($_GET['id']);
        }
        // Redirige al índice después de eliminar la receta
        header("Location: index.php");
        break;

    case 'edit':
        // Redirige al formulario de edición de receta
        if (isset($_GET['id'])) {
            $recipe_id = $_GET['id'];
            $recipe = $recipeManager->getRecipeById($recipe_id);

            if ($recipe) {
                // Muestra el formulario de edición si la receta existe
                require 'views/edit_recipe.php';
            } else {
                // Si no existe la receta, redirige a la lista
                header('Location: index.php');
                exit();
            }
        }
        break;

    default:
        // Muestra la lista de recetas
        $recipes = $recipeManager->getAllRecipes();
        require 'views/task_list.php';
        break;
}
?>

