<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se ha recibido un ID válido para eliminar
if (isset($_GET['id'])) {
    $session_id = $_GET['id'];

    // Eliminar la sesión de la base de datos
    $stmt = $pdo->prepare('DELETE FROM sessions WHERE id = ? AND user_id = ?');
    if ($stmt->execute([$session_id, $_SESSION['user_id']])) {
        header('Location: dashboard.php');
        exit;
    } else {
        die('Error al eliminar la sesión.');
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>
