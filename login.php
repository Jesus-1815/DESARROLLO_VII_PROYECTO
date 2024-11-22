<?php
session_start();
require 'config.php'; // Archivo que contiene client_id y client_secret
require 'src/Database.php';
require 'src/UserManager.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Crear instancia de UserManager
        $userManager = new UserManager();
    
        // Intentar iniciar sesión
        try {
            $user = $userManager->login($email, $password);
            
            if ($user) {
                // Guardar solo el ID y la información esencial del usuario en la sesión
                $_SESSION['user_id'] = $user->getId(); // Suponiendo que tienes un método getId() en la clase User
                $_SESSION['username'] = $user->getUsername(); // Guardar el nombre de usuario, si lo necesitas
                header('Location: index.php');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos.'; // Mostrar un mensaje si el login falla
            }
        } catch (Exception $e) {
            // Mostrar el error si ocurre
            $error = "Error al intentar iniciar sesión: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form action="" method="POST">
        <input type="text" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <a href="registro.php">Registrarse</a>
    <a href="forgot-password.php">¿Olvidaste tu contraseña?</a>
</body>
</html>
