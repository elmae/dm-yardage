<?php
session_start();

// Si el usuario está autenticado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DM Yardage - Página Principal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Bienvenido a DM Yardage</h1>
        <p class="text-center">Tu herramienta definitiva para registrar y analizar tus sesiones de golf.</p>

        <div class="d-flex justify-content-center mt-4">
            <a href="login.php" class="btn btn-primary mx-2">Iniciar Sesión</a>
            <a href="register.php" class="btn btn-secondary mx-2">Registrarse</a>
        </div>

        <div class="mt-5 text-center">
            <img src="https://via.placeholder.com/600x300" alt="Imagen del sitio" class="img-fluid">
        </div>
    </div>
</body>
</html>
