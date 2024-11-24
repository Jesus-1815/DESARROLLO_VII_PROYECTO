<?php
// Iniciamos el buffer de salida
ob_start();
?>
<div class="recipe-container">
    <style>
        /* Contenedor general */
.recipe-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
    background-color: #fff;
}

/* Encabezado */
.recipe-header {
    text-align: center;
    margin-bottom: 20px;
}

.recipe-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}

.rating {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px 0;
}

.star {
    color: gold;
    font-size: 20px;
    margin-right: 2px;
}

.star.empty {
    color: #ddd;
}

.rating-average {
    margin-left: 10px;
    font-size: 16px;
    color: #666;
}

.prep-time {
    font-size: 16px;
    color: #555;
}

/* Imagen y descripción */
.recipe-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.recipe-im img {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.recipe-description {
    font-size: 16px;
    color: #444;
    text-align: justify;
}

/* Pasos */
.recipe-steps {
    margin-top: 20px;
}

.recipe-steps h2 {
    font-size: 22px;
    margin-bottom: 10px;
    color: #333;
}

.recipe-steps ol {
    padding-left: 20px;
    color: #555;
}

.recipe-steps li {
    margin-bottom: 10px;
    line-height: 1.5;
}

/* Comentarios */
.recipe-comments {
    margin-top: 20px;
}

.recipe-comments h2 {
    font-size: 22px;
    margin-bottom: 10px;
    color: #333;
}

.comment {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    background-color: #f9f9f9;
}

.comment p {
    margin: 0;
    font-size: 14px;
    color: #555;
}

    </style>
    <?php if (!$recipe): ?>
        <p>Recipe not found.</p>
    <?php else: ?>
    <div class="recipe-header">
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe->getTitle()); ?></h1>
        <div class="rating">
            <span class="star">&#9733;</span>
            <span class="rating-average">(4.0)</span>
        </div>
        <p class="prep-time">⏲️ <?php echo htmlspecialchars($recipe->getPrepTime()); ?></p>
    </div>

    <div class="recipe-body">
        <div class="recipe-im">
            <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de la receta">
        </div>
        <p class="recipe-description"><?php echo htmlspecialchars($recipe->getDescription()); ?></p>
    </div>

    <h2>Ingredientes</h2>
    <ol>
    <?php foreach ($ingredientes as $ingredient): ?>
        <li>
            <?php 
                echo htmlspecialchars($ingredient['quantity']) . ' ';
                echo htmlspecialchars($ingredient['unit']) . ' ';
                echo htmlspecialchars($ingredient['name']);
            ?>
        </li>
    <?php endforeach; ?>
</ol>

    <h2>Pasos</h2>
        <ol>
            <?php foreach ($steps as $step): ?>
                <li><?php echo htmlspecialchars($step['step_text']); ?></li>
            <?php endforeach; ?>
        </ol>

    <div class="recipe-comments">
        <h2>Comentarios</h2>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay comentarios aún.</p>
        <?php endif; ?>
    </div>
    <?php
    
    if ($_SESSION['user_id'] == $recipe->getUserId()): ?>
        <!-- Botones para el creador de la receta -->
        <a href="index.php?action=edit&id=<?= $recipe->getId() ?>" class="btn">Editar</a>
        <a href="index.php?action=delete&id=<?= $recipe->getId() ?>" class="btn" onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>
    <?php else: ?>
        <!-- Botones para los usuarios que no son creadores -->
        <button onclick="mostrarFormularioComentario()">Agregar comentario</button>
        <button onclick="mostrarRating()">Calificar receta</button>
    <?php endif; ?>
    <script>
        function mostrarFormularioComentario() {
    // Mostrar el modal con el formulario
    let modal = document.getElementById('commentModal');
    modal.style.display = 'block';
    }

    function mostrarRating() {
    // Mostrar el sistema de calificación
    let rating = prompt("Califica la receta de 1 a 5 estrellas:");

    if (rating >= 1 && rating <= 5) {
        // Redirigir al index.php con la acción rate y el rating
        window.location.href = 'index.php?action=rate&id=<?php echo $_GET['id']; ?>&rating=' + rating;
    } else {
        alert("Por favor, ingresa un número válido entre 1 y 5.");
    }
    }

    </script>
    <!-- Modal para agregar comentario -->
    <div id="commentModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('commentModal').style.display='none'">&times;</span>
            <h2>Agregar Comentario</h2>
            <form action="<?php echo BASE_URL; ?>/index.php?action=comment&id="method="POST">
                <textarea name="comment_text" placeholder="Escribe tu comentario" required></textarea>
                <input type="hidden" name="recipe_id" value="<?php echo $_GET['id']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <button type="submit">Enviar comentario</button>
            </form>
        </div>
    </div>

    <?php endif; 
    $content = ob_get_clean();
    require 'layout.php';?>
</div>
