<?php
session_start(); // Asegúrate de llamar a session_start() al principio

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no hay una sesión de usuario, redirige a login.php
    header("Location: login.php");
    exit(); // Detén la ejecución del script actual
}

// Adjust here based on the actual structure of $_SESSION['usuario']
$assigned_store = $_SESSION['usuario']; // Directly use 'usuario' if it contains the store ID
$rutaCarpeta = 'uploads/' . $assigned_store . '/';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files Display <?php echo isset($selectedStore) ? $selectedStore : ''; ?></title>
    <!-- Agrega los estilos de Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="slick-master/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="slick-master/slick/slick-theme.css"/>
    <style>
        .slick-carousel-container {
            text-align: center;
        }
        .slick-carousel img,
        .slick-carousel video {
            max-width: 100%;
            max-height: 100vh;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>

<div class="slick-carousel-container">
    <div class="slick-carousel">
        <?php
        // Verificar si la carpeta existe y leer los archivos
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
                        echo 'Tu navegador no soporta el tag de video.';
                        echo '</video>';
                    }

                    echo '</div>';
                }
            }
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="slick-master/slick/slick.min.js"></script>
<script>
    $(document).ready(function(){
        var $slickCarousel = $('.slick-carousel');
        $slickCarousel.slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: false,
            centerMode: true,
            fade: true,
            adaptiveHeight: true,
            speed: 100,
            pauseOnHover: false
        });

        function ajustarAutoplayParaVideo(videoElement) {
            var duracionVideo = videoElement.duration * 1000; // Duración en milisegundos
            $slickCarousel.slick('slickSetOption', 'autoplaySpeed', duracionVideo, true);
            videoElement.play();
        }

        $slickCarousel.on('afterChange', function(event, slick, currentSlide){
            var $currentSlide = $(slick.$slides[currentSlide]);
            var $currentVideo = $currentSlide.find('video');

            if ($currentVideo.length > 0) {
                if ($currentVideo.get(0).readyState > 0) {
                    ajustarAutoplayParaVideo($currentVideo.get(0));
                } else {
                    $currentVideo.on('loadedmetadata', function() {
                        ajustarAutoplayParaVideo(this);
                    });
                }
            } else {
                $slickCarousel.slick('slickSetOption', 'autoplay','autoplaySpeed', 5000, true);
                $slickCarousel.slick('slickPlay');
            }

            if (currentSlide === slick.$slides.length - 1) {
            setTimeout(function(){
                window.location.reload(); // Recarga la página
            }, 5000); // Espera 5 segundos antes de recargar la página
        }
        });

        $slickCarousel.find('video').each(function() {
            $(this).on('ended', function() {
                $slickCarousel.slick('slickNext');
            });
        });
    });
</script>


</body>
</html>
