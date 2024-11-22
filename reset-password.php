<?php
require 'config.php';
require 'src/Database.php';
require 'src/UserManager.php';

$message = '';
$validToken = false;
$token = $_GET['token'] ?? '';

if (empty($token)) {
    header('Location: login.php');
    exit;
}

$userManager = new UserManager();
$tokenInfo = $userManager->verifyRecoveryToken($token);

if (!$tokenInfo) {
    $message = "El enlace ha expirado o no es válido.";
} else {
    $validToken = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    if ($password !== $confirmPassword) {
        $message = "Las contraseñas no coinciden.";
    } else {
        try {
            $userManager->updatePassword($tokenInfo['user_id'], $password);
            $message = "Contraseña actualizada correctamente.";
            header("refresh:2;url=login.php");
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h1>Restablecer Contraseña</h1>
    <?php if ($validToken): ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Nueva contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            <button type="submit">Cambiar contraseña</button>
        </form>
    <?php endif; ?>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <a href="login.php">Volver al login</a>
</body>
</html>