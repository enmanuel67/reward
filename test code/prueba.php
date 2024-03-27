<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos</title>
    <link rel="stylesheet" href="styles/upload_styles.css">
    <style>
        .checkbox-group {
            display: none;
        }
    </style>
    <script>
        function toggleCheckboxGroup() {
            var checkboxGroup = document.getElementById('checkbox-group');
            checkboxGroup.style.display = checkboxGroup.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
<div id="menu">
        <h2>Menú</h2>
        <a href="upload.php">Home</a>
        <div class="dropdown">
            <a href="#">stores</a>
            <div class="dropdown-content">
                <a href="show_files.php?store=36960">NSN: 36960</a>
                <a href="show_files.php?store=11565">NSN: 11565</a>
                <a href="#">Subopción 3</a>
            </div>
        </div>
        <a href="login.php">sing out</a> 
    </div>

    <div class="container">
    <div>
            <h1>Subir Archivos</h1>
            <!-- Resto del formulario -->
            <form action="upload.php" method="post" enctype="multipart/form-data">
                
                <label for="stores" onclick="toggleCheckboxGroup()" style="color:blue; ">Selecciona la tienda:</label>
                <div class="checkbox-group" id="checkbox-group">
                    <label for="store_36960"><input type="checkbox" name="stores[]" id="store_36960" value="36960"> Tienda 36960</label>
                    <label for="store_11565"><input type="checkbox" name="stores[]" id="store_11565" value="11565"> Tienda 11565</label>
                </div>

                <br>

                <label for="file">Selecciona archivos:</label>
                <input type="file" name="file[]" id="file" multiple required>

                <button type="submit" name="submit">Subir Archivos</button>
            </form>
        </div>

        <?php
        session_start(); // Asegurarse de iniciar la sesión al principio

        // Asegurar que el usuario esté logueado
        if (!isset($_SESSION['usuario'])) {
            echo "Por favor, inicia sesión para subir archivos.";
            exit(); // Detener la ejecución si el usuario no está logueado
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_FILES["file"]["name"][0])) {
                $selectedStores = isset($_POST["stores"]) ? $_POST["stores"] : array();
                $validStores = ["36960", "11565"];

                foreach ($selectedStores as $store) {
                    if (!in_array($store, $validStores)) {
                        echo "Tienda no válida.";
                        exit;
                    }

                    foreach ($_FILES["file"]["name"] as $index => $filename) {
                        $usuario = $_SESSION['usuario']; // Usar el nombre de usuario de la sesión
                        $date = date('YmdHis'); // Fecha y hora actual
                        $newFilename = $usuario . '+' . $date . '+' . $filename; // Nuevo nombre de archivo

                        $targetDir = "uploads/" . $store . "/";
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        $targetFile = $targetDir . basename($newFilename);

                        if (file_exists($targetFile)) {
                            echo "El archivo " . htmlspecialchars($newFilename) . " ya existe en la tienda " . htmlspecialchars($store) . ".<br>";
                        } else {
                            if (move_uploaded_file($_FILES["file"]["tmp_name"][$index], $targetFile)) {
                                echo "El archivo " . htmlspecialchars($newFilename) . " ha sido subido con éxito a la tienda " . htmlspecialchars($store) . ".<br>";
                            } else {
                                echo "Hubo un error al subir el archivo " . htmlspecialchars($filename) . " a la tienda " . htmlspecialchars($store) . ".<br>";
                            }
                        }
                    }
                }
            } else {
                echo "Por favor, selecciona al menos un archivo para subir.<br>";
            }
        }
        ?>
    </div>
</body>
</html>
