<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start(); // Start the session at the beginning

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Conéctate a la base de datos
    $mysqli = new mysqli("localhost", "root", "", "deal");

    // Verifica la conexión
    if ($mysqli->connect_error) {
        die("Error connecting to the database " . $mysqli->connect_error);
    }

    // Consulta para verificar si el usuario ya existe
    $checkUserQuery = "SELECT * FROM usuarios WHERE username = ?";
    $checkUserStmt = $mysqli->prepare($checkUserQuery);
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserResult = $checkUserStmt->get_result();

    if ($checkUserResult->num_rows > 0) {
        // Username exists, prepare error message
        $_SESSION['error'] = "User already exists, Try with Another one.";
    } else {
        // Hash de la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Consulta para insertar el nuevo usuario en la base de datos
        $insertUserQuery = "INSERT INTO usuarios (username, userpass, user_email) VALUES (?, ?, ?)";
        $insertUserStmt = $mysqli->prepare($insertUserQuery);
        $insertUserStmt->bind_param("sss", $username, $hashedPassword, $email);

        // Ejecutar la consulta
        if ($insertUserStmt->execute()) {
            $_SESSION['success'] = "User registered succesfully";
        } else {
            $_SESSION['error'] = "Error registering user.";
        }
    }

    // Cerrar conexiones
    $checkUserStmt->close();
    if (isset($insertUserStmt)) {
        $insertUserStmt->close();
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link rel="stylesheet" href="styles/register_styles.css">
</head>
<body>
    <div class="container">
        <h1>User Register</h1>

        <form action="register.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Sign Up</button>
        </form>

        <!-- Display messages -->
        
        <?php if (isset($_SESSION['success'])): ?>
            <p><?php echo $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <p><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?> 
        <?php endif; ?>

        <p>Already have an account? <a href="login.php">Sign in</a></p>
    </div>
</body>
</html>