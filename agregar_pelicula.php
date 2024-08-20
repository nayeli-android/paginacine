<?php
//session_start();


// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'cine');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Agregar película
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $duracion = intval($_POST['duracion']);
    $genero = $conn->real_escape_string($_POST['genero']);
    
    // Subida de la imagen
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagen);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Comprobar si el archivo es una imagen real
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Comprobar el tamaño del archivo
    if ($_FILES["imagen"]["size"] > 500000) {
        echo "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir solo ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    // Comprobar si $uploadOk está establecido en 0 por un error
    if ($uploadOk == 0) {
        echo "Lo siento, tu archivo no fue subido.";
    // Si todo está bien, intenta subir el archivo
    } else {
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            echo "El archivo ". htmlspecialchars( basename( $_FILES["imagen"]["name"])). " ha sido subido.";
        } else {
            echo "Lo siento, hubo un error al subir tu archivo.";
        }
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO peliculas (titulo, descripcion, duracion, genero, imagen) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssiss', $titulo, $descripcion, $duracion, $genero, $target_file);

    if ($stmt->execute()) {
        echo "Película añadida con éxito.";
    } else {
        echo "Error al añadir la película: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nueva Película</title>
    <link rel="stylesheet" href="agregar.css">
</head>
<body>
<div class="header">
        <div class="logo">
            <h1>CinemaX</h1>
        </div>
    </div>

    <div class="container">
        <h2>Añadir Nueva Película</h2>
        <form action="agregar_pelicula.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="duracion">Duración (minutos):</label>
            <input type="number" id="duracion" name="duracion" required>

            <label for="genero">Género:</label>
            <input type="text" id="genero" name="genero" required>

            <label for="imagen">Imagen de la película:</label>
            <input type="file" id="imagen" name="imagen" required>

            <input type="submit" value="Añadir Película">
        </form>
    </div>
</body>
</html>
