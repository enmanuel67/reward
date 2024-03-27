<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header("Location: login.php");
    exit();
}

// Acceder a los datos del usuario almacenados en la sesión
$usuario = $_SESSION['usuario'];
$email = $_SESSION['email'] ?? 'No especificado'; // Usa el operador de fusión null para valores no definidos
$nombre = $_SESSION['nombre'] ?? 'Anónimo';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles\profile_styles.css">
    <title>User Profile</title>
</head>
<body>
<?php include 'menu.php'; ?>
<br>

<div class="profile">
    <h2>User Profile</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($usuario); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($nombre); ?></p>
    <!-- Añade más campos según necesites -->
</div>

</body>
</html>
