<?php
require_once 'Database.php';
require_once 'src/RecipeManager.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe_id = $_POST['recipe_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $prep_time = $_POST['prep_time'];
    $steps = $_POST['steps'];
    $ingredients = $_POST['ingredients'];
    $quantities = $_POST['quantities'];
    $units = $_POST['units'];

    try {
        $recipeManager = new RecipeManager();

        // Actualizar la receta
        $recipeManager->updateRecipe($recipe_id, $title, $description, $prep_time, $steps);

        // Actualizar ingredientes
        for ($i = 0; $i < count($ingredients); $i++) {
            $recipeManager->addIngredientToRecipe($recipe_id, $ingredients[$i], $quantities[$i], $units[$i]);
        }

        echo "Receta guardada con éxito.";
    } catch (Exception $e) {
        echo "Error al guardar la receta: " . $e->getMessage();
    }
}
?>

