<?php
// Incluye tus archivos de clase
require_once 'src/Database.php';
require_once 'src/User.php';
require_once 'src/UserManager.php';
require_once 'src/Recipe.php';
require_once 'src/RecipeManager.php';
require_once 'src/Step.php';
require_once 'src/StepManager.php';
require_once 'src/Ingredient.php';
require_once 'src/IngredientManager.php';
require_once 'src/RecipeIngredient.php';
require_once 'src/RecipeIngredientManager.php';
require_once 'src/Comment.php';
require_once 'src/CommentManager.php';
require_once 'src/Rating.php';
require_once 'src/RatingManager.php';
require_once 'config.php'; // Asegúrate de que la configuración de la base de datos está incluida

try {
    // Conexión a la base de datos (ajusta según tu configuración)
    $db = new PDO("mysql:host=localhost;dbname=db_proyecto", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Instancia los managers
    $userManager = new UserManager($db);
    $recipeManager = new RecipeManager($db);
    $stepManager = new StepManager($db);
    $ingredientManager = new IngredientManager($db);
    $recipeIngredientManager = new RecipeIngredientManager($db);
    $commentManager = new CommentManager($db);
    $ratingManager = new RatingManager($db);

    // === PRUEBAS PARA USERS ===
    echo "<h3>Pruebas de Usuarios:</h3>";
    $username = 'nuevo_user';
    $email = 'nuevo_user@example.com';
    $password = 'password123';

    if ($userManager->createUser($username, $email, $password)) {
        echo "Usuario creado exitosamente.<br>";
    } else {
        echo "Error al crear el usuario.<br>";
    }

    $user = $userManager->getUserById(1);
    echo $user ? "Usuario encontrado: " . $user->getUsername() . "<br>" : "Usuario no encontrado.<br>";

    // === PRUEBAS PARA RECIPES ===
    echo "<h3>Pruebas de Recetas:</h3>";
    $userId = 1; // Cambiar por un usuario válido en tu BD
    $recipe = new Recipe([
        'user_id' => $userId,
        'title' => 'Receta de prueba',
        'description' => 'Descripción de prueba',
        'prep_time' => '00:45:00',
    ]);
    $recipeManager->save($recipe);
    echo "Receta creada exitosamente.<br>";

    $recipes = $recipeManager->getAll();
    echo "Recetas disponibles:<br>";
    foreach ($recipes as $recipe) {
        echo "Título: " . $recipe->getTitle() . "<br>";
    }

   // === PRUEBAS PARA STEPS ===
echo "<h3>Pruebas de Pasos:</h3>";
$recipeId = 1; // Cambiar por una receta válida en tu BD
$stepManager->addStep($recipeId, "Primer paso de la receta");
$stepManager->addStep($recipeId, "Segundo paso de la receta");
echo "Pasos agregados correctamente.<br>";

$steps = $stepManager->getStepsByRecipe($recipeId);
echo "Pasos de la receta:<br>";
foreach ($steps as $step) {
    echo "Orden: " . $step['step_order'] . ", Descripción: " . $step['description'] . "<br>";
}


    // === PRUEBAS PARA INGREDIENTS ===
    echo "<h3>Pruebas de Ingredientes:</h3>";
    $ingredientManager->createIngredient("Tomate");
    $ingredientManager->createIngredient("Cebolla");
    echo "Ingredientes agregados correctamente.<br>";

    $ingredients = $ingredientManager->getAllIngredients();
    echo "Ingredientes disponibles:<br>";
    foreach ($ingredients as $ingredient) {
        echo "ID: " . $ingredient->getId() . ", Nombre: " . $ingredient->getName() . "<br>";
    }

    // === PRUEBAS PARA RECIPE_INGREDIENTS ===
    echo "<h3>Pruebas de Ingredientes por Receta:</h3>";
    $recipeIngredientManager->addIngredientToRecipe(1, 1, "2 unidades");
    $recipeIngredientManager->addIngredientToRecipe(1, 2, "1 unidad");
    echo "Ingredientes añadidos a la receta correctamente.<br>";

    $recipeIngredients = $recipeIngredientManager->getIngredientsByRecipe(1);
    echo "Ingredientes de la receta:<br>";
    foreach ($recipeIngredients as $ri) {
        echo "Ingrediente: " . $ri['name'] . ", Cantidad: " . $ri['quantity'] . "<br>";
    }

    // === PRUEBAS PARA COMMENTS ===
    echo "<h3>Pruebas de Comentarios:</h3>";
    $commentManager->addComment(1, 1, "¡Esta receta está genial!");
    echo "Comentario añadido correctamente.<br>";

    $comments = $commentManager->getCommentsByRecipe(1);
    echo "Comentarios de la receta:<br>";
    foreach ($comments as $comment) {
        echo "Comentario: " . $comment->getContent() . ", Usuario ID: " . $comment->getUserId() . "<br>";
    }

    // === PRUEBAS PARA RATINGS ===
    echo "<h3>Pruebas de Valoraciones:</h3>";
    $ratingManager->addRating(1, 1, 5);
    echo "Valoración añadida correctamente.<br>";

    $averageRating = $ratingManager->getAverageRatingByRecipe(1);
    echo "Valoración promedio de la receta: " . $averageRating . "<br>";

} catch (PDOException $e) {
    echo "Error de base de datos: " . $e->getMessage();
}
