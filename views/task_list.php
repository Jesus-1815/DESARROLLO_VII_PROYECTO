<?php
// Iniciamos el buffer de salida
ob_start();
?>
<div class="task-list">
    <h2>Mis Recetas</h2>

    <!-- Formulario de búsqueda -->
    <form method="get" action="index.php">
        <input type="hidden" name="action" value="list"> <!-- Necesario para no cambiar la acción al buscar -->
        <input type="text" name="query" placeholder="Buscar por nombre, ingrediente o tiempo de preparación" value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit" class="btn">Buscar</button>
    </form>

    <a href="index.php?action=create" class="btn">Nueva Receta</a>

    <ul class="recipe-list">
        <?php if (count($recipes) > 0): ?>
            <?php foreach ($recipes as $recipe): ?>
                <li class="recipe-item">
                    <h3><?= htmlspecialchars($recipe->getTitle()) ?></h3>

                    <!-- Muestra la imagen principal de la receta -->
                    <?php if ($recipe->getImagePath()): ?>
                        <div class="recipe-image">
                            <p>Ruta de la imagen: <?= htmlspecialchars($recipe->getImagePath()) ?></p>
                            <img src="<?= htmlspecialchars($recipe->getImagePath()) ?>" alt="Imagen de <?= htmlspecialchars($recipe->getTitle()) ?>">
                        </div>
                    <?php endif; ?>

                    <p><strong>Tiempo de preparación:</strong> <?= htmlspecialchars($recipe->getPrepTime()) ?> minutos</p>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($recipe->getDescription()) ?></p>
                    <div>
                        <a href="index.php?action=view&id=<?= $recipe->getId() ?>" class="btn">Ver</a>
                        <?php if ($_SESSION['user_id'] == $recipe->getUserId()): ?>  
                            <a href="index.php?action=edit&id=<?= $recipe->getId() ?>" class="btn">Editar</a> <!-- Enlace de editar -->
                            <a href="index.php?action=delete&id=<?= $recipe->getId() ?>" class="btn" onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>                        
                        <?php endif; ?> 
                       
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No se encontraron recetas.</li>
        <?php endif; ?>
    </ul>
</div>
<?php
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require 'layout.php';
?>

