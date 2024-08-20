<?php
session_start();

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'cine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar las películas disponibles
$sql = "SELECT id, titulo, descripcion, duracion, genero, imagen FROM peliculas";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas Disponibles</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>CinemaX</h1>
        </div>
    </div>
    <h1>Disfruta de las películas disponibles de estas semana</h1>
    <div class="peliculas">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="pelicula">
                <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
                <p><strong>Género:</strong> <?php echo htmlspecialchars($row['genero']); ?></p>
                <p><strong>Duración:</strong> <?php echo htmlspecialchars($row['duracion']); ?> minutos</p>
                <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                <?php if ($row['imagen']): ?>
                    <img src="images/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen de la película">
                <?php endif; ?>
                <a href="detalles_pelicula.php?id=<?php echo $row['id']; ?>">Ver detalles</a>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
