<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener los detalles de la sesión
if (isset($_GET['id'])) {
    $session_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM sessions WHERE id = ? AND user_id = ?');
    $stmt->execute([$session_id, $_SESSION['user_id']]);
    $session = $stmt->fetch();

    if (!$session) {
        die('Sesión no encontrada.');
    }

    // Obtener los detalles de los hoyos
    $stmt = $pdo->prepare('SELECT * FROM hole_details WHERE session_id = ?');
    $stmt->execute([$session_id]);
    $holes = $stmt->fetchAll();
} else {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Sesión - DM Yardage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Detalles de la Sesión</h2>
        <p><strong>Fecha:</strong> <?php echo $session['date_of_game']; ?></p>
        <p><strong>Campo:</strong> <?php echo $session['course_name']; ?></p>
        <p><strong>Total de Golpes:</strong> <?php echo $session['total_strokes']; ?></p>

        <h3>Detalles de los Hoyos</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hoyo</th>
                    <th>Golpes</th>
                    <th>Distancia</th>
                    <th>Palo Usado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($holes as $hole): ?>
                    <tr>
                        <td><?php echo $hole['hole_number']; ?></td>
                        <td><?php echo $hole['strokes']; ?></td>
                        <td><?php echo $hole['distance']; ?></td>
                        <td><?php echo $hole['club_used']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
    </div>
</body>
</html>
