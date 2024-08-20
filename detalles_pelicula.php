<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'cine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la película
$pelicula_id = intval($_GET['id']);

// Consultar los detalles de la película
$sql = "SELECT titulo, descripcion, duracion, genero, imagen FROM peliculas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $pelicula_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($titulo, $descripcion, $duracion, $genero, $imagen);
$stmt->fetch();

if ($stmt->num_rows == 0) {
    echo "Película no encontrada.";
    exit;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?></title>
    <link rel="stylesheet" href="detalles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($titulo); ?></h1>
    <p><strong>Género:</strong> <?php echo htmlspecialchars($genero); ?></p>
    <p><strong>Duración:</strong> <?php echo htmlspecialchars($duracion); ?> minutos</p>
    <p><?php echo htmlspecialchars($descripcion); ?></p>
    <?php if ($imagen): ?>
        <img src="images/<?php echo htmlspecialchars($imagen); ?>" alt="Imagen de la película">
    <?php endif; ?>
    <a href="seleccion_asientos.php?pelicula_id=<?php echo $pelicula_id; ?>">Seleccionar Asientos</a>
</body>
</html>
<?php
$conn->close();
?>
