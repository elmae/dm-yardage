<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener los detalles de la sesión para editar
if (isset($_GET['id'])) {
    $session_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM sessions WHERE id = ? AND user_id = ?');
    $stmt->execute([$session_id, $_SESSION['user_id']]);
    $session = $stmt->fetch();

    if (!$session) {
        die('Sesión no encontrada.');
    }

    // Manejar la actualización de la sesión
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_of_game = $_POST['date_of_game'];
        $course_name = $_POST['course_name'];
        $total_strokes = $_POST['total_strokes'];

        // Validar el formulario
        if (empty($date_of_game) || empty($course_name) || empty($total_strokes)) {
            $errors[] = 'Todos los campos son obligatorios.';
        } else {
            // Actualizar la sesión en la base de datos
            $stmt = $pdo->prepare('UPDATE sessions SET date_of_game = ?, course_name = ?, total_strokes = ? WHERE id = ? AND user_id = ?');
            if ($stmt->execute([$date_of_game, $course_name, $total_strokes, $session_id, $_SESSION['user_id']])) {
                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = 'Error al actualizar la sesión.';
            }
        }
    }
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
    <title>Editar Sesión - DM Yardage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Sesión de Juego</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="edit_session.php?id=<?php echo $session['id']; ?>" method="post">
            <div class="mb-3">
                <label for="date_of_game" class="form-label">Fecha del Juego</label>
                <input type="date" name="date_of_game" class="form-control" id="date_of_game" value="<?php echo $session['date_of_game']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="course_name" class="form-label">Nombre del Campo</label>
                <input type="text" name="course_name" class="form-control" id="course_name" value="<?php echo $session['course_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="total_strokes" class="form-label">Total de Golpes</label>
                <input type="number" name="total_strokes" class="form-control" id="total_strokes" value="<?php echo $session['total_strokes']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Sesión</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
    </div>
</body>
</html>
