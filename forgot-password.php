<?php
require 'vendor/autoload.php';
require 'src/Database.php';
require 'src/UserManager.php';
require 'src/MailSender.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $message = 'Por favor, ingrese su email.';
    } else {
        try {
            $userManager = new UserManager();
            $token = $userManager->generateRecoveryToken($email);
            
            // Enviar email usando la nueva clase
            $mailSender = new MailSender();
            $mailSender->sendRecoveryEmail($email, $token);
            
            $message = "Se ha enviado un enlace de recuperación a tu email.";
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
    <title>Recuperar Contraseña</title>
</head>
<body>
    <h1>Recuperar Contraseña</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="Tu email" required>
        <button type="submit">Enviar enlace de recuperación</button>
    </form>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <a href="login.php">Volver al login</a>
</body>
</html>
