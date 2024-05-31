<!-- 
    Adaptar la plantilla a los datos del modal 
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href=".../datos_plantilla/estilos.css">
    <title><?php echo $template->get('titulo_portafolio') ?></title>
</head>

<body>
    <!-- Menu  -->
    <div class="contenedor-header">

        <header>
            <div class="logo">
                <a href="#"><?php echo $template->get('nombres') ?></a>
            </div>
            <nav id="nav">
                <ul>
                    <li><a href="#inicio" onclick="seleccionar()">Inicio</a></li>
                    <li><a href="#sobre-mi" onclick="seleccionar()">Sobre mi</a></li>
                    <li><a href="#skills" onclick="seleccionar()">Habilidades</a></li>
                    <li><a href="#proyectos" onclick="seleccionar()">Proyectos</a></li>
                </ul>
            </nav>
            <div class="nav-responsive" onclick="mostrarOcultarMenu()">
                <i class="fa-solid fa-bars"></i>
            </div>
        </header>
    </div>

    <!-- Inicio  -->
    <section id="inicio" class="inicio">
        <div class="contenido-banner">
            <div class="contenedor-img">
                <img src='<?php echo $template->get('ruta_imagen') ?>' alt="">
            </div>
            <h1><?php echo $template->get('nombres') ?></h1>
            <!-- Modificar o borrar. -->
            <h2>Ingeniero de Software</h2>
        </div>
    </section>

    <!-- Sobre mi  -->
    <section id="sobre-mi" class="sobre-mi">
        <div class="contenido-seccion">
            <!-- Modificar, datos provenientes del modal -->
            <h2>Sobre mi</h2>
            <p><span>Hola, soy Josué García</span>. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Labore
                illo
                vero odit possimus ut unde, tempore rerum, placeat, ad obcaecati fugiat beatae quis accusamus iste
                facilis ullam dolor! Magni, libero?</p>

            <div class="fila">
                <!-- Datos personales  -->
                <div class="col">
                    <h3>Datos personales</h3>
                    <ul>
                        <li><Strong>Cumpleaños</Strong>
                            14-04-2024</li>
                        <li><Strong>Telefono</Strong>
                            2555 545454</li>
                        <li><Strong>Email</Strong>
                            cv@example.com</li>
                        <li><Strong>Direccion</Strong>
                            Ambato, Ecuador</li>
                    </ul>
                </div>
                <!-- Intereses  -->
                <div class="col">
                    <h3>Intereses</h3>
                    <div class="contenedor-intereses">
                        <div class="intereses">
                            <i class="fa-solid fa-gamepad"></i>
                            <span>Juegos</span>
                        </div>
                        <div class="intereses">
                            <i class="fa-solid fa-headphones"></i>
                            <span>Musica</span>
                        </div>
                        <div class="intereses">
                            <i class="fa-solid fa-plane"></i>
                            <span>Viajes</span>
                        </div>
                    </div>
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
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>75%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>HTML && CSS</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>89%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>Java</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>89%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>Photoshop</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>50%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Habilidades profesionales  -->
                <div class="col">
                    <h3>Profesionales</h3>
                    <div class="skill">
                        <span>Comunicacion</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>75%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>Trabajo en equipo</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>89%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>Creatividad</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>89%</span>
                            </div>
                        </div>
                    </div>
                    <div class="skill">
                        <span>Dedicacion</span>
                        <div class="barra-skill">
                            <div class="progreso">
                                <span>50%</span>
                            </div>
                        </div>
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