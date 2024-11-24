<?php
require 'src/Database.php';
require_once 'src/UserManager.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar los datos
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Crear instancia de UserManager
        $userManager = new UserManager();

        // Intentar crear el usuario
        try {
            $userManager->createUser($username, $email, $password);
            // Redirigir al usuario después de un registro exitoso
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            // Mostrar el error si ocurre
            $error = 'Error al registrar el usuario: ' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
</head>
<body class="register-background">
    <div class="form-container">
        <h1>Registro</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="text" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <div class="links">
            <a href="login.php">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>

