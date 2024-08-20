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
$pelicula_id = intval($_GET['pelicula_id']);

// Consultar los asientos disponibles
$sql = "SELECT asiento FROM boletos WHERE pelicula_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $pelicula_id);
$stmt->execute();
$result = $stmt->get_result();
$asientos_ocupados = [];
while ($row = $result->fetch_assoc()) {
    $asientos_ocupados[] = $row['asiento'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $asiento_seleccionado = $_POST['asiento'];

    if (in_array($asiento_seleccionado, $asientos_ocupados)) {
        echo "El asiento ya está ocupado.";
    } else {
        // Registrar la compra
        $usuario_id = $_SESSION['user_id'];
        $sql = "INSERT INTO boletos (usuario_id, pelicula_id, asiento) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $usuario_id, $pelicula_id, $asiento_seleccionado);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Compra realizada con éxito. Tu asiento es: " . htmlspecialchars($asiento_seleccionado);
        } else {
            echo "Error al realizar la compra.";
        }
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Asientos</title>
    <link rel="stylesheet" href="asientos.css">
</head>
<body>
    <h1>Selecciona tu Asiento</h1>
    <form action="seleccion_asientos.php?pelicula_id=<?php echo $pelicula_id; ?>" method="post">
        <label for="asiento">Asiento:</label>
        <select name="asiento" id="asiento" required>
            <?php for ($i = 1; $i <= 50; $i++): ?>
                <?php $asiento = "A" . $i; ?>
                <option value="<?php echo $asiento; ?>" <?php echo in_array($asiento, $asientos_ocupados) ? 'disabled' : ''; ?>>
                    <?php echo $asiento; ?> <?php echo in_array($asiento, $asientos_ocupados) ? '(Ocupado)' : ''; ?>
                </option>
            <?php endfor; ?>
        </select><br>
        <button type="submit">Comprar</button>
    </form>
</body>
</html>
