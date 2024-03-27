<?php
session_start();

$errorMessage = "";

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "deal");

if ($mysqli->connect_error) {
    die("Conection error! " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta para obtener el email y el nombre adicional a las demás columnas confirmadas
    // Consulta para obtener el email, el nombre, la tienda asignada y el estado del usuario
$query = "SELECT username, userpass, user_email, assigned_store, status FROM usuarios WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['userpass'])) {
                if ($user['status'] == 'A') {
                    // El usuario está activo, procede con el inicio de sesión
                    $_SESSION['usuario'] = $username;
                    $_SESSION['email'] = $user['user_email']; // Guardar email del usuario de la DB en la sesión
                    $_SESSION['nombre'] = $user['realname']; // Asumiendo que también recuperas el nombre real del usuario
            
                    // Decisiones de redirección personalizadas en acuerdo a 'assigned_store'
                    if ($user['assigned_store'] == '36960') {
                        header("Location: login_with_archive.php");
                    } elseif ($user['assigned_store'] == '11565') {
                        header("Location: login_with_archive.php");
                    } else {
                        // Página por defecto si no se detecta un patrón de redirección
                        header("Location: upload.php");
                    }
                } else {
                    // El usuario está inactivo, muestra un mensaje de error
                    $errorMessage = "The account is innactive, please contact Administration";
                }
            } else {
                // Nombre de usuario o contraseña erróneos
                $errorMessage = "Username or password incorrect, try again.";
            }
        }
    }            

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles\login_styles.css">
    <style>
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
 
<div class="container">
    <h1>Welcome to the login sytem</h1>
    <form action="login.php" method="post" enctype="multipart/form-data">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label> 
        <input type="password" id="password" name="password" required>

        <button type="submit">Sign in </button>
    </form>

    <div class="error-message"><?php echo $errorMessage; ?></div>
    <p>Don't have an account?<a href="register.php">Sign up here</a></p>
</div>

</body>
</html>
