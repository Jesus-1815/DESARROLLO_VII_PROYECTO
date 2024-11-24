<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recetas</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
</head>
<body>
<div class="container">

<header>
    <div class="header-left">
        <h2><?php 
            $pageTitle = basename($_SERVER['PHP_SELF']) == 'index.php' ? 'Inicio' : 'Buscar Recetas'; 
            echo $pageTitle; 
        ?></h2>
        <?php if ($pageTitle !== 'Inicio'): ?>
            <a href="index.php" class="home-link">
    <i class="fas fa-home"></i> Volver al Inicio
</a>  <!-- Botón que redirige a la página principal -->
        <?php endif; ?>
    </div>

    <div class="header-center">
    <a href="support.php" class="support-btn">
    <i class="fas fa-headset"></i> Soporte
</a> <!-- Botón que redirige al formulario de soporte -->
    </div>

    <div class="header-right">
    <a href="logout.php" class="logout-btn">
    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
</a>
    </div>
    
</header>
</div>
<main>
    <?php echo $content; ?>
</main>
<footer>
    <p>&copy; 2024 Recetas App</p>
</footer>
<script src="<?php echo BASE_URL; ?>/public/assets/js/main.js"></script>
</body>
</html>


