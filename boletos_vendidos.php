<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'cine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar los boletos vendidos
$sql = "SELECT usuarios.nombre, usuarios.apellido, peliculas.titulo, boletos.asiento, boletos.fecha_compra 
        FROM boletos 
        JOIN usuarios ON boletos.usuario_id = usuarios.id 
        JOIN peliculas ON boletos.pelicula_id = peliculas.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletos Vendidos</title>
    <link rel="stylesheet" href="boletos.css">
</head>
<body>
    <h1>Boletos Vendidos</h1>
    <table>
        <tr>
            <th>Usuario</th>
            <th>Película</th>
            <th>Asiento</th>
            <th>Fecha de Compra</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                <td><?php echo htmlspecialchars($row['asiento']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_compra']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php
$conn->close();
?>
