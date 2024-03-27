<?php
// Verifica si se han proporcionado parámetros
if (!isset($_POST['store']) || !isset($_POST['filename'])) {
    echo "Parámetros no válidos.";
    exit();
}

// Obtiene la tienda y el nombre del archivo de los parámetros
$store = $_POST['store'];
$filename = $_POST['filename'];

// Construye la ruta completa del archivo
$filePath = "uploads/" . $store . "/" . $filename;

// Verifica si el archivo existe y lo borra
if (file_exists($filePath)) {
    unlink($filePath);
    // Redirige a show_files.php con un parámetro indicando que el archivo fue borrado
    header("Location: show_files.php?store=$store&deleted=1");
    exit();
} else {
    echo "El archivo no existe.";
}
?>
