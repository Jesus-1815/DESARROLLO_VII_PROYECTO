<?php
// Iniciamos el buffer de salida
ob_start();
?>
<div class="task-list">
    <h2>Mis Recetas</h2>
    <a href="index.php?action=create" class="btn">Nueva Receta</a>
    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li class="recipe-item">
                <h3><?= htmlspecialchars($recipe->getTitle()) ?></h3>
                <p><strong>Tiempo de preparación:</strong> <?= htmlspecialchars($recipe->getPrepTime()) ?> minutos</p>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($recipe->getDescription()) ?></p>
                <div>
                    <a href="index.php?action=view&id=<?= $recipe->getId() ?>" class="btn">Ver</a>
                    <a href="index.php?action=edit&id=<?= $recipe->getId() ?>" class="btn">Editar</a> <!-- Enlace de editar -->
                    <a href="index.php?action=delete&id=<?= $recipe->getId() ?>" class="btn" onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require 'layout.php';
?>
