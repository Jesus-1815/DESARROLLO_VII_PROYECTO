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
        <h2><?php $pageTitle = basename($_SERVER['PHP_SELF']) == 'index.php' ? 'Inicio' : 'Buscar Recetas'; echo $pageTitle; ?></h2>
        <?php if ($pageTitle !== 'Inicio'): ?>
            <a href="index.php" class="home-link">Volver al Inicio</a>
        <?php endif; ?>
    </div>

    <div class="header-right">
        <a href="logout.php" class="logout-btn">Cerrar sesión</a>
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
