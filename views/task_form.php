<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>
<div class="task-form">
    <h2>Crear Nueva Receta</h2>
    <form action="index.php?action=create" method="post">
        <input type="text" name="title" placeholder="Título de la receta" required>
        <button type="submit" class="btn">Crear Receta</button>
    </form>
    <br>
    <a href="index.php" class="btn">Volver</a>
</div>
<?php
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require 'layout.php';
?>