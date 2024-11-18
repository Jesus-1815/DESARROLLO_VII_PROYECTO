<?php
// Incluye tus archivos de clase
require_once 'src/Database.php';
require_once 'src/User.php';
require_once 'src/UserManager.php';
require_once 'src/Recipe.php';
require_once 'src/RecipeManager.php';
require_once 'config.php'; // Asegúrate de que la configuración de la base de datos está incluida

try {
    // Conexión a la base de datos (ajusta según tu configuración)
    $db = new PDO("mysql:host=localhost;dbname=db_proyecto", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Instancia los managers
    $userManager = new UserManager($db);
    $recipeManager = new RecipeManager($db);

    // Crear un nuevo usuario
    /*$username = 'nuevo_us';
    $email = 'nuevo_usu@example.com';
    $password = 'contraseña123';

    if ($userManager->createUser($username, $email, $password)) {
        echo "Usuario creado exitosamente.<br>";
    } else {
        echo "Error al crear el usuario.<br>";
    }
        */
    // Obtener el usuario por ID
    $user = $userManager->getUserById(1);
    if ($user) {
        echo "Usuario encontrado: " . $user->getUsername() . "<br>";
    } else {
        echo "Usuario no encontrado.<br>";
    }

    // Crear una nueva receta
    $userId = 7; // Asegúrate de que este ID corresponde a un usuario existente
    $title = 'Mi preceta';
    $description = 'Una receta delicioparar.';
    $preparationTime = '00:30:00';

    $recipe = new Recipe([
        'user_id' => $userId,
        'title' => $title,
        'description' => $description,
        'prep_time' => $preparationTime,
    ]);

    $recipeManager->save($recipe);
    echo "Receta creada exitosamente.<br>";

    // Obtener todas las recetas
    $recipes = $recipeManager->getAll();
    echo "Recetas disponibles:<br>";
    foreach ($recipes as $recipe) {
        echo "Título: " . $recipe->getTitle() . ", Descripción: " . $recipe->getDescription() . ", Tiempo de preparación: " . $recipe->getPrepTime() . "<br>";
    }

} catch (PDOException $e) {
    echo "Error de base de datos: " . $e->getMessage();
}