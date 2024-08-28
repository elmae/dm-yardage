<?php
require_once '../config/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si la sesión ID ha sido proporcionada
if (!isset($_GET['session_id'])) {
    header('Location: dashboard.php');
    exit;
}

$session_id = $_GET['session_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hole_number = $_POST['hole_number'];
    $strokes = $_POST['strokes'];
    $distance = $_POST['distance'];
    $club_used = $_POST['club_used'];

    // Validar los datos del formulario
    if (empty($hole_number) || empty($strokes) || empty($distance) || empty($club_used)) {
        $errors[] = 'Todos los campos son obligatorios.';
    } else {
        // Insertar los detalles del hoyo en la base de datos
        $stmt = $pdo->prepare('INSERT INTO hole_details (session_id, hole_number, strokes, distance, club_used) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$session_id, $hole_number, $strokes, $distance, $club_used])) {
            // Redirigir de vuelta para agregar más detalles o ver los detalles
            header("Location: add_hole_details.php?session_id=$session_id");
            exit;
        } else {
            $errors[] = 'Error al registrar los detalles del hoyo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Detalles del Hoyo - DM Yardage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Detalles del Hoyo</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="add_hole_details.php?session_id=<?php echo $session_id; ?>" method="post">
            <div class="mb-3">
                <label for="hole_number" class="form-label">Número de Hoyo</label>
                <input type="number" name="hole_number" class="form-control" id="hole_number" required>
            </div>
            <div class="mb-3">
                <label for="strokes" class="form-label">Golpes Realizados</label>
                <input type="number" name="strokes" class="form-control" id="strokes" required>
            </div>
            <div class="mb-3">
                <label for="distance" class="form-label">Distancia del Tiro (en yardas)</label>
                <input type="number" name="distance" class="form-control" id="distance" required>
            </div>
            <div class="mb-3">
                <label for="club_used" class="form-label">Palo Usado</label>
                <input type="text" name="club_used" class="form-control" id="club_used" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Hoyo</button>
            <a href="view_session.php?id=<?php echo $session_id; ?>" class="btn btn-secondary">Ver Sesión</a>
        </form>
    </div>
</body>
</html>
