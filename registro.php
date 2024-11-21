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
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="text" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <a href="login.php">Iniciar sesión</a>
</body>
</html>
