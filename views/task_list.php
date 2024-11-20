<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>
<div class="task-list">
    <h2>Mis Recetas</h2>
    <a href="index.php?action=create" class="btn">Nueva Receta</a>
    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li class="<?= $recipe['is_completed'] ? 'completed' : '' ?>">
                <span><?= htmlspecialchars($recipe['title']) ?></span>
                <div>
                    <a href="index.php?action=toggle&id=<?= $recipe['id'] ?>" class="btn">
                        <?= $recipe['is_completed'] ? '✓' : '○' ?>
                    </a>
                    <a href="index.php?action=delete&id=<?= $task['id'] ?>" class="btn" onclick="return confirm('¿Eliminar esta tarea?')">🗑</a>
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