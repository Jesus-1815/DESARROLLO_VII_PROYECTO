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
    <style>/* Estilo general para la página de recuperación de contraseña */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Contenedor principal */
h1 {
    color: #333;
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Estilo para el formulario */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

/* Estilo para el campo de email */
input[type="email"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

/* Estilo para el botón de envío */
button {
    background-color: #007BFF;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    margin-top: 10px;
}

button:hover {
    background-color: #0056b3;
}

/* Estilo para el mensaje de error o éxito */
p {
    text-align: center;
    font-size: 14px;
    color: #333;
    margin-top: 15px;
}

/* Enlace para volver al login */
a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #007BFF;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
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
