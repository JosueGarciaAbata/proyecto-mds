
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./../datos_plantilla/estilos.css">
    <title>My creative portfolio</title>
</head>

<body>
    <!-- Menu  -->
    <div class="contenedor-header">

        <header>
            <div class="logo">
                <a href="#"><span id="name-user"></span></a>
            </div>
            <nav id="nav">
                <ul>
                    <li><a href="#inicio" class="main-menu-item" ">Inicio</a></li>
                    <li><a href="#sobre-mi" class="main-menu-item">Sobre mi</a></li>
                    <li><a href="#skills" class="main-menu-item">Habilidades</a></li>
                    <li><a href="#proyectos" class="main-menu-item">Proyectos</a></li>
                </ul>
            </nav>
            <div class="nav-responsive" class="main-menu-nav">
                <i class="fa-solid fa-bars"></i>
            </div>
        </header>
    </div>

    <!-- Inicio  -->
    <section id="inicio" class="inicio">
        <div class="contenido-banner">
            <div class="contenedor-img">
                <img src='#' alt="" id="img-portfolio">
            </div>
            <h1></h1>
        </div>
    </section>

    <!-- Sobre mi  -->
    <section id="sobre-mi" class="sobre-mi">
        <div class="contenido-seccion">
            <!-- Modificar, datos provenientes del modal -->
            <h2>Sobre mi</h2>
            <p>Hola soy <span id="about-me"></span></p>
            <div class="fila">
                <!-- Datos personales  -->
                <div class="col">
                    <ul>
                        <li><Strong>Email: </Strong><span id="email-user"></span>
                    </ul>
                </div>
            </div>
            <button>
                <a href="#" target="_blank" id="download-cv" rel="noopener noreferrer">
                    <span class="overlay"></span>
                    Descargar Curriculum <i class="fa-solid fa-download"></i>
                </a>
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
                <div class="col technique">
                    <h3>Técnicas</h3>
                </div>
                <!-- Habilidades profesionales  -->
                <div class="col social">
                    <h3>Blandas</h3>
                    
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
    <!-- Codigo para generar dinamicamente los proyectos del portafolio  -->
    <!-- 
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
    -->
    <script src="./../datos_plantilla/script.js"></script>
</body>

</html>