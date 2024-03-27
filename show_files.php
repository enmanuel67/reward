<?php

// Start the session
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header("Location: login.php");
    exit(); // Detén la ejecución del script actual
}

$usuario = $_SESSION['usuario'];
// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header("Location: login.php");
    exit(); // Detén la ejecución del script actual
}

// Check if the selected store is set in the URL parameter
if (isset($_GET['store'])) {
    $selectedStore = $_GET['store'];
} elseif (isset($_SESSION['selectedStore'])) {
    $selectedStore = $_SESSION['selectedStore'];
} else {
    echo "Selected store not found.";
    exit();
}

$targetDir = "uploads/" . $selectedStore . "/";
$deletedDir = "deleted/";
$files = scandir($targetDir);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploaded - NSN <?php echo isset($selectedStore) ? $selectedStore : ''; ?></title>
    <link rel="stylesheet" href="styles/show_files_styles.css">
</head>

<body>
<?php include 'menu.php'; ?>
<h1>File Uploaded - NSN: <?php echo isset($selectedStore) ? $selectedStore : ''; ?></h1>
    <div class="container">
        
        
         
        <?php
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $filePath = $targetDir . $file;
                $fileType = mime_content_type($filePath);

                echo '<div class="file-item">';
                if (strpos($fileType, "image") !== false || strpos($fileType, "video") !== false) {
                    // If it's an image or video
                    if (strpos($fileType, "image") !== false) {
                        // If it's an image
                        echo '<img src="' . $filePath . '" alt="' . basename($file) . '">';
                    } elseif (strpos($fileType, "video") !== false) {
                        // If it's a video
                        echo '<video controls>
                                <source src="' . $filePath . '" type="' . $fileType . '">
                                Tu navegador no soporta el tag de video.
                              </video>';
                    }

                    // Formulario para borrar el archivo
                    echo '<form class="delete-form" action="delete_file.php" method="post">';
                    echo '<input type="hidden" name="store" value="' . $selectedStore . '">';
                    echo '<input type="hidden" name="filename" value="' . $file . '">';
                    echo '<button type="submit">Borrar</button>';
                    echo '</form>';
                }
                echo '</div>';
            }
        }
        ?>
    </div>
</body>

</html>