<?php
session_start();
// Asegúrate de incluir la lógica necesaria para validar la sesión del usuario aquí

$assigned_store = $_SESSION['usuario'];
$rutaCarpeta = 'uploads/' . $assigned_store . '/';

if(file_exists($rutaCarpeta)) {
    $archivos = scandir($rutaCarpeta);
    foreach ($archivos as $archivo) {
        if ($archivo != "." && $archivo != ".." && !is_dir($rutaCarpeta . $archivo)) {
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
            echo '<div>';
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo '<img src="' . $rutaCarpeta . $archivo . '" alt="' . $archivo . '">';
            } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                echo '<video width="100%" height="auto" autoplay muted playsinline>';
                echo '<source src="' . $rutaCarpeta . $archivo . '" type="video/' . $extension . '">';
                echo '</video>';
            }
            echo '</div>';
        }
    }
}
?>
