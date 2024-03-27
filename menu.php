<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos Subidos - Tienda</title>
    <link rel="stylesheet" href="styles/menu_styles.css">
</head>
<body>

<div id="menuToggle">&#9776;</div> <!-- Botón de menú hamburguesa -->
<h4>Menu</h4>
<div id="menu">
    <!-- Contenido del menú aquí -->
    <h2>Menú</h2>
    <div class="online">
        <p><strong>Online:</strong> <?php echo htmlspecialchars($usuario); ?></p>
    </div>
    <a href="profile.php">Profile</a>
    <a href="upload.php">Home</a>
    <div class="dropdown">
        <a href="#" class="button-link">stores</a>
        <div class="dropdown-content" id="dropdown-content">
            <a href="show_files.php?store=36960">NSN: 36960</a>
            <a href="show_files.php?store=11565">NSN: 11565</a>
            <a href="#">Subopción 3</a>
        </div>
    </div>
    <a href="logout.php">Sign Out</a> 
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var menuToggle = document.getElementById('menuToggle');
    var menu = document.getElementById('menu');

    menuToggle.addEventListener('click', function() {
        // Alternar la clase para abrir o cerrar el menú
        menu.classList.toggle('menu-close');

        // Ajustar la posición del menú cuando se abre o se cierra
        if (menu.classList.contains('menu-close')) {
            menu.style.left = '-250px'; // Mostrar el menú deslizándolo hacia adentro
        } else {
            menu.style.left = '0'; // Ocultar el menú deslizándolo hacia afuera
        }
    });
});
</script>

</body>
</html>
