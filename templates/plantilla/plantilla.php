
<?php
if(empty($_GET) || empty($_GET["id-portfolio"]))
    die("Acceso no autorizado");

require_once ('../../../procesarInformacion/conexion.php');



function generarHTMLHabilidades($habilidades) {
    // Inicia la estructura HTML
    $html = '<div class="fila">';

    // Genera la columna para habilidades técnicas
    if (isset($habilidades['tecnicas']) && is_array($habilidades['tecnicas'])) {
        $html .= '<div class="col">';
        $html .= '<h3>Técnicas</h3>';
        foreach ($habilidades['tecnicas'] as $habilidad) {
            $html .= '<div class="skill">';
            $html .= '<span>' . htmlspecialchars($habilidad) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }

    // Genera la columna para habilidades sociales (o blandas)
    if (isset($habilidades['sociales']) && is_array($habilidades['sociales'])) {
        $html .= '<div class="col">';
        $html .= '<h3>Blandas</h3>';
        foreach ($habilidades['sociales'] as $habilidad) {
            $html .= '<div class="skill">';
            $html .= '<span>' . htmlspecialchars($habilidad) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }

    // Cierra la estructura HTML
    $html .= '</div>';

    return $html;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href=".../datos_plantilla/estilos.css">
    <!-- <title><?php echo $template->get('titulo_portafolio') ?></title> -->
    <title><?php echo $_SESSION["title"]?></title>
</head>

<body>
    <!-- Menu  -->
    <div class="contenedor-header">

        <header>
            <div class="logo">
                <a href="#"><?php echo $_SESSION["nombres"]?></a>
            </div>
            <nav id="nav">
                <ul>
                    <li><a href="#inicio" class="main-menu-item" ">Inicio</a></li>
                    <li><a href="#sobre-mi" class="main-menu-item">Sobre mi</a></li>
                    <li><a href="#skills" class="main-menu-item">Habilidades</a></li>
                    <li><a href="#proyectos" class="main-menu-item">Proyectos</a></li>
                </ul>
            </nav>
            <div class="nav-responsive" class="main-menu-nav" onclick="mostrarOcultarMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </header>
    </div>

    <!-- Inicio  -->
    <section id="inicio" class="inicio">
        <div class="contenido-banner">
            <div class="contenedor-img">
                <img src='<?php echo $_SESSION["fotoP"]?>' alt="">
            </div>
            <h1><?php echo $_SESSION["nombres"]?></h1>
            <!-- Modificar o borrar. -->
            <h2>Ingeniero de Software</h2>
        </div>
    </section>

    <!-- Sobre mi  -->
    <section id="sobre-mi" class="sobre-mi">
        <div class="contenido-seccion">
            <!-- Modificar, datos provenientes del modal -->
            <h2>Sobre mi</h2>
            <p>Hola soy <?php echo $_SESSION["nombres"].", ".$_SESSION["mensaje"]?>. </p>

            <div class="fila">
                <!-- Datos personales  -->
                <div class="col">
                    <ul>
                        <li><Strong>Email: </Strong>
                        <?php echo $_SESSION["correo"]?></li>
                    </ul>
                </div>
            </div>
            <button>
                <span class="overlay"></span>
                Descargar Curriculum <i class="fa-solid fa-download"></i>
            </button>
        </div>
    </section>

    <!-- Habilidades -->
    <section id="skills" class="skills">
        <!-- Todo esto en teoria se debe  generar dinamicamente  -->
        <div class="contenido-seccion">
            <h2>Habilidades</h2>
            <div class="fila">
                <!-- Habilidades tecnicas  -->
                <div class="col">
                    <h3>Técnicas</h3>
                    <div class="skill">
                        <span>JavaScript</span>
                    </div>
                    <div class="skill">
                        <span>HTML && CSS</span>
                    </div>
                    <div class="skill">
                        <span>Java</span>
                    </div>
                    <div class="skill">
                        <span>Photoshop</span>
                    </div>
                </div>

                <!-- Habilidades profesionales  -->
                <div class="col">
                    <h3>Blandas</h3>
                    <div class="skill">
                        <span>Comunicacion</span>
                    </div>
                    <div class="skill">
                        <span>Trabajo en equipo</span>
                    </div>
                    <div class="skill">
                        <span>Creatividad</span>
                    </div>
                    <div class="skill">
                        <span>Dedicacion</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portafolio  -->
    <section id="proyectos" class="proyectos">
        <!-- Esto se debe generar dinamicamente  -->
        <div class="contenido-seccion">
            <h2>Proyectos</h2>
            <div class="galeria">
                <!-- Aqui se va a generar dinamicamente los proyectos  -->
            </div>
    </section>

    <!-- Pie de pagina  -->
    <footer>
        <a href="#inicio" class="arriba">
            <i class="fa-solid fa-angles-up"></i>
        </a>
    </footer>

    <script src="../datos_plantillas/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Codigo para generar dinamicamente los proyectos del portafolio  -->
    <script>
        $(document).ready(function () {
            // Solicitud AJAX para obtener los datos de los proyectos
            $.ajax({
                url: 'obtener_proyectos.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Itera sobre los datos y agrega dinámicamente los proyectos a la galería
                    $.each(data, function (index, proyecto) {
                        var proyectoHTML = '<div class="proyecto">' +
                            '<img src="../datos_plantillas/template1/img/p1.jpg" alt="">' +
                            '<div class="overlay">' +
                            '<h3>' + proyecto.titulo + '</h3>' +
                            '<p>' + proyecto.descripcion + '</p>' +
                            '</div>' +
                            '</div>';
                        $('.galeria').append(proyectoHTML);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener los proyectos:', error, xhr, status);
                }
            });
        });
    </script>

</body>

</html>