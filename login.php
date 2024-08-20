<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'cine');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Buscar usuario en la base de datos
    $sql = "SELECT id, password, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $tipo);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_tipo'] = $tipo;
            header("Location: index.php");
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no registrado.";
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
    <title>Iniciar Sesión - CinemaX</title>
    <link rel="stylesheet" href="login.css">
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
            <h2>Iniciar Sesión</h2>
            <form action="login.php" method="POST">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit" class="btn">Iniciar Sesión</button>
            </form>

            <p class="register-link">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 CinemaX. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
