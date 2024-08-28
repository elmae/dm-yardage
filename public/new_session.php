<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_of_game = $_POST['date_of_game'];
    $course_name = $_POST['course_name'];
    $total_strokes = $_POST['total_strokes'];
    
    // Validar el formulario
    if (empty($date_of_game) || empty($course_name) || empty($total_strokes)) {
        $errors[] = 'Todos los campos son obligatorios.';
    } else {
        // Insertar la sesión en la base de datos
        $stmt = $pdo->prepare('INSERT INTO sessions (user_id, date_of_game, course_name, total_strokes) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$_SESSION['user_id'], $date_of_game, $course_name, $total_strokes])) {
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Error al registrar la sesión.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Sesión - DM Yardage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Nueva Sesión de Juego</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="new_session.php" method="post">
            <div class="mb-3">
                <label for="date_of_game" class="form-label">Fecha del Juego</label>
                <input type="date" name="date_of_game" class="form-control" id="date_of_game" required>
            </div>
            <div class="mb-3">
                <label for="course_name" class="form-label">Nombre del Campo</label>
                <input type="text" name="course_name" class="form-control" id="course_name" required>
            </div>
            <div class="mb-3">
                <label for="total_strokes" class="form-label">Total de Golpes</label>
                <input type="number" name="total_strokes" class="form-control" id="total_strokes" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Sesión</button>
        </form>
    </div>
</body>
</html>
