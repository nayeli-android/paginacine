<?php
session_start();

// Verifica si el usuario es un administrador
if ($_SESSION['tipo'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - CinemaX</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1>CinemaX</h1>
        </div>
    </div>

    <div class="admin-welcome">
        <h2>Bienvenido al Panel de Administración</h2>
    </div>

    <div class="admin-options">
        <button onclick="location.href='gestionar_peliculas.php'">Gestionar Películas</button>
        <button onclick="location.href='boletos_vendidos.php'">Ver Boletos Vendidos</button>
    </div>

    <footer>
        <p>© 2024 CinemaX. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
