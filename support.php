<?php
// Activar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia sesión y verifica autenticación
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Define BASE_URL si no está definido
if (!defined('BASE_URL')) {
    define('BASE_URL', '/PROYECTO');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h2>Formulario de Soporte</h2>
        <a href="index.php" class="home-link">Volver al Inicio</a>
    </header>

    <main>
    <form action="<?php echo BASE_URL; ?>/src/submit_support.php" method="POST">
            <label for="issue">Describe tu problema:</label>
            <textarea name="issue" id="issue" rows="5" required></textarea>
            <button type="submit">Enviar</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Recetas App</p>
    </footer>
</div>
<script src="<?php echo BASE_URL; ?>/public/assets/js/main.js"></script>
</body>
</html>
