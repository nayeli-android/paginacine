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

// Eliminar película
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM peliculas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();

    echo "Película eliminada con éxito.";
}

// Consultar las películas
$sql = "SELECT id, titulo, descripcion, duracion, genero FROM peliculas";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Películas</title>
    <link rel="stylesheet" href="gestion.css">
</head>
<body>
<div class="header">
        <div class="logo">
            <h1>CinemaX</h1>
        </div>
        <div class="nav">
            <a href="agregar_pelicula.php" class="btn">Añadir Nueva Película</a>
        </div>
    </div>
    <div class="container">
        <h2>Gestionar Películas</h2>
        <table>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Duración</th>
                <th>Género</th>
                <th>Acciones</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($row['duracion']); ?> minutos</td>
                    <td><?php echo htmlspecialchars($row['genero']); ?></td>
                    <td class="actions">
                        <a href="editar_pelicula.php?id=<?php echo $row['id']; ?>">Editar</a>
                        <a href="gestionar_peliculas.php?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta película?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <footer>
        <p>© 2024 CinemaX. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
