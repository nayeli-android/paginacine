<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'cine');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validar y filtrar datos
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    // Insertar en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $nombre, $apellido, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        echo "Error en el registro.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CinemaX</title>
    <link rel="stylesheet" href="registro.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <h1>CinemaX</h1>
            </div>
        </div>
    </header>

    <section class="login-section">
        <div class="login-container">
            <h2>Crear Cuenta</h2>
            <form action="registro.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit" class="btn">Registrarse</button>
            </form>

            <p class="register-link">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 CinemaX. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
