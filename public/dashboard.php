<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener las sesiones del usuario
$stmt = $pdo->prepare('SELECT * FROM sessions WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$sessions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DM Yardage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Mis Sesiones de Juego</h2>
        <a href="new_session.php" class="btn btn-primary mb-3">Registrar Nueva Sesión</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Campo</th>
                    <th>Total de Golpes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td><?php echo $session['date_of_game']; ?></td>
                        <td><?php echo $session['course_name']; ?></td>
                        <td><?php echo $session['total_strokes']; ?></td>
                        <td>
                            <a href="view_session.php?id=<?php echo $session['id']; ?>" class="btn btn-info">Ver</a>
                            <a href="edit_session.php?id=<?php echo $session['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="delete_session.php?id=<?php echo $session['id']; ?>" class="btn btn-danger">Eliminar</a>
                            <a href="add_hole_details.php?session_id=<?php echo $session['id']; ?>" class="btn btn-primary">Agregar Hoyos</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
