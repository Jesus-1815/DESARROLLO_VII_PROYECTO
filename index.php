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
require_once 'src/Recipe.php'; // Asegúrate de incluir la clase Recipe

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Instancia del RecipeManager
$recipeManager = new RecipeManager();

// Manejo de acciones desde la URL
$action = $_GET['action'] ?? 'list'; // Por defecto, se listan las recetas

// Obtenemos el término de búsqueda si existe
$searchQuery = $_GET['query'] ?? ''; // Obtiene el término de búsqueda desde la barra de búsqueda

switch ($action) {
    case 'create':
        // Redirige al formulario para crear una nueva receta
        require 'views/task_form.php';
        break;

        case 'store':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_SESSION['user_id'];
                $title = $_POST['recipe_name'];
                $description = $_POST['description'];
                $prepTime = $_POST['prep_time'];
                $ingredients = $_POST['ingredient'];
                $quantities = $_POST['quantity'];
                $units = $_POST['unit'];
                $steps = $_POST['steps'];
        
                // Crear la receta
                $recipeId = $recipeManager->createRecipe($userId, $title, $description, $prepTime, $ingredients, $steps);
                if (!$recipeId) {
                    die("Error: No se pudo crear la receta.");
                }
        
                // Inserta cada ingrediente con su unidad
                foreach ($ingredients as $index => $ingredientName) {
                    $quantity = $quantities[$index];
                    $unit = $units[$index];
                    // Usamos la función para añadir ingredientes con la unidad
                    $recipeManager->addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit);
                }
        
                // Manejo de imágenes
                if (isset($_FILES['recipe_images']) && $_FILES['recipe_images']['error'][0] === 0) {
                    $images = $_FILES['recipe_images'];
                    $uploadDir = 'uploads/recipes/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
        
                    for ($i = 0; $i < count($images['name']); $i++) {
                        $imageName = uniqid() . '_' . basename($images['name'][$i]);
                        $imagePath = $uploadDir . $imageName;
        
                        if (move_uploaded_file($images['tmp_name'][$i], $imagePath)) {
                            $recipeManager->addImageToRecipe($recipeId, $imagePath);
                        }
                    }
                }
        
                // Redirige al índice (lista de recetas)
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

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Datos principales de la receta
                $recipeId = $_POST['recipe_id'];
                $title = $_POST['recipe_name'];
                $prepTime = $_POST['prep_time'];
                $description = $_POST['description'];
                $steps = $_POST['steps'] ?? []; // Pasos
        
                // Ingredientes
                $ingredients = $_POST['ingredient'] ?? [];
                $quantities = $_POST['quantity'] ?? [];
                $units = $_POST['unit'] ?? [];
        
                // Combina los ingredientes con las cantidades y unidades
                $ingredientData = [];
                foreach ($ingredients as $index => $ingredientName) {
                    $ingredientData[] = [
                        'name' => $ingredientName,
                        'quantity' => $quantities[$index] ?? 0,
                        'unit' => $units[$index] ?? ''
                    ];
                }
        
                // Ahora llama a updateRecipe con todos los parámetros
                $recipeUpdated = $recipeManager->updateRecipe($recipeId, $title, $description, $prepTime, $ingredientData, $steps);
        
                if (!$recipeUpdated) {
                    die("Error al actualizar la receta.");
                }
        
                // Redirige al índice después de actualizar
                header("Location: index.php");
                exit;
            }
            break;
        
             
        case 'view':
    if (isset($_GET['id'])) {
        $recipe = $recipeManager->getRecipeById($_GET['id']);
        $imagen = $recipeManager->getImagesByRecipeId($_GET['id']);
        $imagen = $imagen[0] ?? null;
        $ingredientes = $recipeManager->getIngredientsByRecipeId($_GET['id']);
        $steps = $recipeManager->getStepsByRecipeId($_GET['id']);
        $rating= $recipeManager->getAverageRating($_GET['id']);
        $comment= $recipeManager->getComments($_GET['id']);

        require 'views/ver_receta.php';
    }
    break;


            case 'rate':  
                if (isset($_GET['id']) && isset($_GET['rating'])) {  
                    $recipeId = (int)$_GET['id'];  
                    $rating = (int)$_GET['rating'];
                    $userId = $_SESSION['user_id']; 
                    
                    // Llama al método de calificación  
                    if ($recipeManager->rateRecipe($userId, $recipeId, $rating)) {  
                        // Redirige a la vista de la receta después de calificar  
                        var_dump($recipeId);
                        header("Location: index.php?action=view&id=$recipeId");  
                        exit();  
                    } else {  
                        // Maneja el error si no se pudo calificar  
                        echo "Error al calificar la receta.";  
                    }  
                } else {  
                    // Si no se proporcionan ID o rating, redirige o muestra un mensaje de error  
                    echo "ID de receta o calificación no válidos.";  
                }  
                break;

                case 'comment':  
                    if (isset($_GET['id'])&& isset($_POST['comment'])) {  
                        $recipe_id = (int)$_GET['id']; 
                        $user_id = $_SESSION['user_id']; //ID del usuario de la sesión  
                        $comment = $_POST['comment']; //texto del comentario  
                
                        try {  
                            // Llama al método para agregar el comentario  
                            if ($recipeManager->addComment($recipe_id, $user_id, $comment)) {  
                                header("Location: index.php?action=view&id=$recipe_id");  
                                exit();  
                            } else {  
                    
                                echo "Error al agregar el comentario.";  
                            }  
                        } catch (Exception $e) {  
                            echo "Error al agregar comentario: " . $e->getMessage();  
                        }  
                    } else {  
                        // Maneja el caso en que no se envía el comentario  
                        echo "No se pudo agregar el comentario.";  
                    }  
                    break;
    default:
        $recipes = $recipeManager->getAllRecipes($searchQuery);  // Pasamos el término de búsqueda
        require 'views/task_list.php';
        break;
}
?>

