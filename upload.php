<?php
session_start(); // Asegúrate de llamar a session_start() al principio

$usuario = $_SESSION['usuario'];

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header("Location: login.php");
    exit(); // Detén la ejecución del script actual
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link rel="stylesheet" href="styles/upload_styles.css">
    
    <script>
        // JavaScript functions remain the same
        function toggleCheckboxGroup() {
            var checkboxGroup = document.getElementById('checkbox-group');
            checkboxGroup.style.display = checkboxGroup.style.display === 'none' ? 'block' : 'none';
        }

        function selectAllStores() {
            var checkboxes = document.querySelectorAll('input[name="stores[]"]');
            var selectAllButton = document.getElementById('select-all-button');

            var allChecked = true;
            checkboxes.forEach(function(checkbox) {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });

            selectAllButton.innerText = allChecked ? 'Select All' : 'Deselect All';
        }
    </script>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <div>
        <h1>Upload Files Page</h1>
        <!-- Resto del formulario -->
        <form action="upload.php" method="post" enctype="multipart/form-data">

            <button type="button" onclick="toggleCheckboxGroup()">Select Store:</button>
            <br>
            <div class="checkbox-group" id="checkbox-group" style="display: none;">
                <label for="store_36960"><input type="checkbox" name="stores[]" id="store_36960" value="36960"> Store 36960</label>
                <label for="store_11565"><input type="checkbox" name="stores[]" id="store_11565" value="11565"> Store 11565</label>
                <br>
                <button type="button" onclick="selectAllStores()" id="select-all-button">Select All</button>
            </div>
            <br>
            <label for="file">Select Files:</label>
            <br>
            <input type="file" name="file[]" id="file" multiple required>

            <button type="submit" name="submit">Upload Files</button>
        </form>
    </div>
        <?php


if (!isset($_SESSION['usuario'])) {
    echo "Please log in to upload files.";
    exit(); // Detener ejecución si el usuario no está logueado
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["file"]["name"][0])) {
    $selectedStores = isset($_POST["stores"]) ? $_POST["stores"] : array();
    $validStores = ["36960", "11565"];

    foreach ($_FILES["file"]["name"] as $index => $originalFilename) {
        // Crear una copia temporal del archivo subido
        $tmpFilePath = $_FILES["file"]["tmp_name"][$index];
        $tmpFile = tempnam(sys_get_temp_dir(), 'upload'); // Crear archivo temporal en el directorio de sistema temporal
        if (move_uploaded_file($tmpFilePath, $tmpFile)) {
            foreach ($selectedStores as $store) {
                if (!in_array($store, $validStores)) {
                    echo "Not a valid store: $store<br>";
                    continue; // Si la tienda no es válida, continúa con la siguiente
                }
                
                $usuario = $_SESSION['usuario']; // Usar el nombre de usuario de la sesión
                $date = date('YmdHis'); // Fecha y hora actual
                $newFilename = $usuario . '+' . $date . '+' . uniqid() . '+' . $originalFilename; // Nuevo nombre de archivo
                $targetDir = "uploads/" . $store . "/";
                $targetFile = $targetDir . basename($newFilename);
                
                // Asegurarse de que el directorio exista
                if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
                    echo "Could not create directory for store: $store<br>";
                    continue;
                }
                
                // Intentar copiar el archivo temporal al destino final
                if (copy($tmpFile, $targetFile)) {
                    echo "File " . htmlspecialchars($newFilename) . " has been successfully uploaded to the store " . htmlspecialchars($store) . ".<br>";
                } else {
                    echo "There was an error uploading the file " . htmlspecialchars($originalFilename) . " to the store " . htmlspecialchars($store) . ".<br>";
                }
            }
            // Eliminar el archivo temporal después de procesar todas las tiendas
            unlink($tmpFile);
        } else {
            echo "The file could not be processed " . htmlspecialchars($originalFilename) . ".<br>";
        }
    }
} else {
    echo "Please select at least one file to upload.<br>";
}
?>
    </div>
</body>
</html>

