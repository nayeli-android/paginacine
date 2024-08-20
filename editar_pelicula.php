<?php
session_start();



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

// Actualizar película
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_titulo = trim($_POST['titulo']);
    $nueva_descripcion = trim($_POST['descripcion']);
    $nueva_duracion = intval($_POST['duracion']);
    $nuevo_genero = trim($_POST['genero']);
    $nueva_imagen = basename($_FILES['imagen']['name']);

    // Subir nueva imagen si se proporciona
    if ($nueva_imagen) {
        move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/$nueva_imagen");
    } else {
        $nueva_imagen = $imagen;  // Mantener la imagen anterior si no se sube una nueva
    }

    $sql = "UPDATE peliculas SET titulo = ?, descripcion = ?, duracion = ?, genero = ?, imagen = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssissi', $nuevo_titulo, $nueva_descripcion, $nueva_duracion, $nuevo_genero, $nueva_imagen, $pelicula_id);
    $stmt->execute();

    echo "Película actualizada con éxito.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Película</title>
    <link rel="stylesheet" href="editar.css">
</head>
<body>
    <h1>Editar Película</h1>
    <form action="editar_pelicula.php?id=<?php echo $pelicula_id; ?>" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea><br>

        <label for="duracion">Duración (minutos):</label>
        <input type="number" id="duracion" name="duracion" value="<?php echo htmlspecialchars($duracion); ?>" required><br>

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($genero); ?>" required><br>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*"><br>

        <button type="submit">Actualizar Película</button>
    </form>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
