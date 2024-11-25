<?php
// Iniciamos el buffer de salida
ob_start();

    if (isset($_SESSION['user_id'])): ?>  
        <?php if ($_SESSION['user_id'] != $recipe->getUserId()): ?>  
            <button onclick="mostrarRating()">Calificar receta</button>  
        <?php endif; ?>  
    <?php else: ?>  
        <p>Debes estar logueado para calificar o comentar.</p>  
    <?php endif; ?>  

<div class="recipe-container">
    <style>
      /* Variables para colores */
:root {
  --primary-green: #2ecc71;
  --dark-green: #27ae60;
  --light-green: #a8e6cf;
  --background-green: #f0f9f4;
  --text-dark: #2c3e50;
  --shadow: rgba(46, 204, 113, 0.2);
}

/* Estilos generales y reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background-color: var(--background-green);
  color: var(--text-dark);
  line-height: 1.6;
  text-align: center;
}

/* Header principal */
.header {
  background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px var(--shadow);
  position: relative;
  overflow: hidden;
}

.header::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 10px,
    rgba(255, 255, 255, 0.05) 10px,
    rgba(255, 255, 255, 0.05) 20px
  );
  animation: backgroundMove 20s linear infinite;
}

@keyframes backgroundMove {
  0% { transform: translate(0, 0); }
  100% { transform: translate(50%, 50%); }
}

/* Contenedor principal */
.recipe-container {
  max-width: 1000px;
  margin: 0 auto 3rem auto;
  padding: 2rem;
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 30px var(--shadow);
  position: relative;
}

/* Título y encabezado */
.recipe-title {
  font-size: 2.8rem;
  color: var(--text-dark);
  margin: 1rem 0;
  padding-bottom: 1rem;
  position: relative;
  display: inline-block;
}

.recipe-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 4px;
  background: var(--primary-green);
  border-radius: 2px;
  transition: width 0.3s;
}

.recipe-title:hover::after {
  width: 150px;
}

/* Sistema de rating con animación */
.rating {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin: 1.5rem 0;
}

.star {
  color: #ffd700;
  font-size: 1.8rem;
  animation: starPulse 2s infinite;
}

@keyframes starPulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.prep-time {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--background-green);
  padding: 0.8rem 1.5rem;
  border-radius: 50px;
  font-weight: 500;
  margin: 1rem 0;
}

/* Imagen de la receta */
.recipe-img {
  width: 100%;
  max-width: 700px;
  margin: 2rem auto;
  position: relative;
  overflow: hidden;
  border-radius: 15px;
  box-shadow: 0 10px 30px var(--shadow);
}

.recipe-img img {
  width: 100%;
  height: auto;
  transition: transform 0.5s;
}

.recipe-img:hover img {
  transform: scale(1.05);
}

/* Secciones de contenido */
.recipe-section {
  margin: 3rem 0;
  padding: 2rem;
  background: var(--background-green);
  border-radius: 15px;
  transition: transform 0.3s;
}

.recipe-section:hover {
  transform: translateY(-5px);
}

h2 {
  color: var(--dark-green);
  font-size: 2rem;
  margin-bottom: 1.5rem;
  display: inline-block;
  position: relative;
}

h2::before {
  content: '🌿';
  margin-right: 10px;
}

/* Listas de ingredientes y pasos */
ol, ul {
  list-style-position: inside;
  padding: 0;
  max-width: 600px;
  margin: 0 auto;
  text-align: left;
}

li {
  margin: 1rem 0;
  padding: 1rem;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px var(--shadow);
  transition: transform 0.2s;
}

li:hover {
  transform: translateX(10px);
}

/* Botones de acción */
.action-buttons {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  margin: 2rem 0;
  padding: 1rem;
}

button, .btn {
  background: var(--primary-green);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
}

button:before {
  font-family: "Font Awesome 5 Free";
  font-weight: 900;
}

button[onclick*="mostrarFormularioComentario"]:before {
  content: "💬";
}

button[onclick*="mostrarRating"]:before {
  content: "⭐";
}

button:hover, .btn:hover {
  background: var(--dark-green);
  transform: translateY(-3px) scale(1.05);
  box-shadow: 0 10px 20px var(--shadow);
}

button:active, .btn:active {
  transform: translateY(0) scale(0.95);
}

/* Modal mejorado */
.modal {
  background-color: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(5px);
}

.modal-content {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  max-width: 600px;
  width: 90%;
  margin: 10vh auto;
  position: relative;
  animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-100px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.close {
  position: absolute;
  right: 1.5rem;
  top: 1rem;
  font-size: 2rem;
  color: var(--text-dark);
  cursor: pointer;
  transition: transform 0.3s;
}

.close:hover {
  transform: rotate(90deg);
}

/* Formulario dentro del modal */
textarea {
  width: 100%;
  padding: 1rem;
  border: 2px solid var(--light-green);
  border-radius: 15px;
  font-size: 1rem;
  margin: 1rem 0;
  resize: vertical;
  transition: border-color 0.3s;
}

textarea:focus {
  outline: none;
  border-color: var(--primary-green);
  box-shadow: 0 0 10px var(--shadow);
}

/* Comentarios */
.recipe-comments {
  margin-top: 3rem;
}

.comment {
  background: var(--background-green);
  padding: 1.5rem;
  border-radius: 15px;
  margin: 1rem auto;
  max-width: 700px;
  text-align: left;
  position: relative;
  transition: transform 0.3s;
}

.comment:hover {
  transform: translateX(10px);
}

.comment strong {
  color: var(--dark-green);
}

/* Animación de carga */
@keyframes shimmer {
  0% { background-position: -1000px 0; }
  100% { background-position: 1000px 0; }
}

.loading {
  background: linear-gradient(
    90deg,
    var(--background-green) 0%,
    var(--light-green) 50%,
    var(--background-green) 100%
  );
  background-size: 1000px 100%;
  animation: shimmer 2s infinite;
}

/* Mensajes de estado */
.status-message {
  padding: 1rem;
  border-radius: 10px;
  margin: 1rem 0;
  animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .recipe-container {
    margin: 1rem;
    padding: 1.5rem;
  }

  .recipe-title {
    font-size: 2rem;
  }

  .action-buttons {
    flex-direction: column;
    padding: 0 1rem;
  }

  button, .btn {
    width: 100%;
    justify-content: center;
  }

  .modal-content {
    width: 95%;
    margin: 5vh auto;
  }
}
    </style>
    <?php if (!$recipe): ?>  
    <p>Recipe not found.</p>  
<?php else: ?>  
    <div class="recipe-header">  
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe->getTitle()); ?></h1>  
        <div class="rating">  
        <span class="star">&#9733;</span>
        <?php if ($rating !== null): ?>
            <span class="rating-average">(<?php echo htmlspecialchars($rating); ?>)</span>
        <?php else: ?>
            <span class="rating-average">(Calificación no disponible)</span>
        <?php endif; ?>

        </div>  
        <p class="prep-time">⏲️ <?php echo htmlspecialchars($recipe->getPrepTime()); ?></p>  
    </div>  

    <div class="recipe-body">  
        <div class="recipe-img">  
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
        <?php if (!empty($comment)): ?>  
            <?php foreach ($comment as $comments): ?>  
                <div class="comment">  
                    <p><strong><?php echo htmlspecialchars($comments['username']); ?>:</strong> <?php echo htmlspecialchars($comments['comment']); ?></p>  
                </div>  
            <?php endforeach; ?>  
        <?php else: ?>  
            <p>No hay comentarios aún.</p>  
        <?php endif; ?> 
    </div>  

    <?php if (isset($_SESSION['user_id'])): ?>  
        <?php if ($_SESSION['user_id'] == $recipe->getUserId()): ?>  
            <!-- Botones para el creador de la receta -->  
            <a href="index.php?action=edit&id=<?= $recipe->getId() ?>" class="btn">Editar</a>  
            <a href="index.php?action=delete&id=<?= $recipe->getId() ?>" class="btn" onclick="return confirm('¿Eliminar esta receta?')">Eliminar</a>  
        <?php else: ?>  
            
            <button onclick="mostrarFormularioComentario()">Agregar comentario</button>  
        <?php endif; ?>  
    <?php else: ?>  
        <p>Debes estar logueado para calificar o comentar.</p>  
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
            <form action="<?php echo BASE_URL; ?>/index.php?action=comment&id=<?php echo $_GET['id']; ?>" method="POST">
                <textarea name="comment" placeholder="Escribe tu comentario" required></textarea>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <button type="submit">Enviar comentario</button>
            </form>
        </div>
    </div>

<?php endif;   
$content = ob_get_clean();  
require 'layout.php'; ?>