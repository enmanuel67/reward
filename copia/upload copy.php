<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Subir Archivos</h1>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="store">Selecciona la tienda:</label>
            <select name="store" id="store" required>
                <option value="36960">Tienda 36960</option>
                <option value="11565">Tienda 11565</option>
            </select>

            <br>

            <label for="file">Selecciona un archivo:</label>
            <input type="file" name="file" id="file" required>

            <button type="submit" name="submit">Subir Archivo</button>
        </form>
    </div>
</body>
</html>

<?php
// Start the session
session_start();

// Function to display uploaded files
function displayUploadedFiles($store)
{
    $targetDir = "uploads/" . $store . "/";
    $files = glob($targetDir . '*');

    foreach ($files as $file) {
        $filePath = $targetDir . basename($file);
        $fileType = mime_content_type($filePath);

        echo '<div>';
        if (strpos($fileType, "image") !== false) {
            // If it's an image
            echo '<img src="' . $filePath . '" alt="' . basename($file) . '" style="max-width: 300px; max-height: 300px; margin: 10px;">';
        } elseif (strpos($fileType, "video") !== false) {
            // If it's a video
            echo '<video width="320" height="240" controls autoplay loop muted>
                    <source src="' . $filePath . '" type="' . $fileType . '">
                    Your browser does not support the video tag.
                  </video>';
        }
        echo '</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha subido un archivo
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        // Obtener la tienda seleccionada
        $selectedStore = $_POST["store"];

        // Verificar si la tienda es válida
        if ($selectedStore == "36960" || $selectedStore == "11565") {
            // Manejar la carga de archivos
            $targetDir = "uploads/" . $selectedStore . "/";
            $deletedDir = "deleted/";

            // Obtener la lista de archivos en el directorio de la tienda
            $files = glob($targetDir . '*');

            // Mover cada archivo al directorio "deleted"
            foreach ($files as $file) {
                $filename = basename($file);
                $destination = $deletedDir . $filename;

                // Verificar si el archivo ya existe en el directorio "deleted" y eliminarlo si es necesario
                if (file_exists($destination)) {
                    unlink($destination);
                }

                // Mover el archivo al directorio "deleted"
                rename($file, $destination);
            }

            $targetFile = $targetDir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;

            // Resto del código para manejar la carga de archivos...
            if ($uploadOk == 1) {
                // Move the uploaded file...
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    echo "El archivo " . basename($_FILES["file"]["name"]) . " ha sido subido con éxito a la tienda " . $selectedStore . ".";

                    // Store the selected store in the session
                    $_SESSION['selectedStore'] = $selectedStore;

                    // Redirect to the corresponding show_files.php
                    header("Location: show_files_" . $selectedStore . ".php");
                    exit();
                } else {
                    echo "Hubo un error al subir el archivo.";
                }
            }
        } else {
            echo "Tienda no válida.";
        }
    } else {
        // Handle the case when no file is selected
        echo "Por favor, selecciona un archivo antes de intentar subirlo.";
    }
}
?>
