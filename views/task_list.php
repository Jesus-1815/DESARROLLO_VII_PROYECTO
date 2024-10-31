<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>
<div class="task-list">
    <h2>Mis Tareas</h2>
    <a href="index.php?action=create" class="btn">Nueva Tarea</a>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li class="<?= $task['is_completed'] ? 'completed' : '' ?>">
                <span><?= htmlspecialchars($task['title']) ?></span>
                <div>
                    <a href="index.php?action=toggle&id=<?= $task['id'] ?>" class="btn">
                        <?= $task['is_completed'] ? '✓' : '○' ?>
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