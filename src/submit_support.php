<?php
// Inicia sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Incluye la configuración
require_once __DIR__ . '/../config.php';
 // Ajusta la ruta si es necesario

// Procesa el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue = trim($_POST['issue']);

    if (!empty($issue)) {
        try {
            // Conexión a la base de datos usando las constantes de config.php
            $db = new PDO(DB_DSN, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Inserta el problema en la tabla de soporte
            $stmt = $db->prepare("INSERT INTO support_requests (user_id, issue, created_at) VALUES (:user_id, :issue, NOW())");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':issue' => $issue,
            ]);

            // Redirige con mensaje de éxito
            header("Location: ../support.php?success=1");
            exit;

        } catch (PDOException $e) {
            die("Error al procesar la solicitud de soporte: " . $e->getMessage());
        }
    } else {
        echo "Por favor, describe tu problema.";
    }
}
?>

