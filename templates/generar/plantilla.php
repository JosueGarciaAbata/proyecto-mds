<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../datos_plantillas/template1/estilos.css">
    <title>Portfolio</title>
</head>

<body>
    <!-- Menu  -->
    <div class="contenedor-header">

        <header>
            <div class="logo">
                <a href="#">Josué García</a>
            </div>
            <nav id="nav">
                <ul>
                    <li><a href="#inicio" onclick="seleccionar()">Inicio</a></li>
                    <li><a href="#sobre-mi" onclick="seleccionar()">Sobre mi</a></li>
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
                <img src="../datos_plantillas/template1/img/hero.png" alt="">
            </div>
            <h1>Josué García</h1>
            <h2>Ingeniero de Software</h2>
            <div class="redes">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </section>

    <!-- Sobre mi  -->
    <section id="sobre-mi" class="sobre-mi">
        <div class="contenido-seccion">
            <h2>Sobre mi</h2>
            <p><span>Hola, soy Josué García</span>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Labore illo
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
        <div class="contenido-seccion">
            <h2>Habilidades</h2>
            <div class="fila">
                <!-- Habilidades tecnicas  -->
                <div class="col">
                    <h3>Habilidades técnicas</h3>
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
        <div class="contenido-seccion">
            <h2>Proyectos</h2>
            <div class="galeria">
                <div class="proyecto">
                    <img src="../datos_plantillas/template1/img/p1.jpg" alt="">
                    <div class="overlay">
                        <h3>Diseño creativo</h3>
                        <p>Fotografia</p>
                    </div>
                </div>

                <div class="proyecto">
                    <img src="../datos_plantillas/template1/img/p2.jpg" alt="">
                    <div class="overlay">
                        <h3>Diseño creativo</h3>
                        <p>Fotografia</p>
                    </div>
                </div>

                <div class="proyecto">
                    <img src="../datos_plantillas/template1/img/p3.jpg" alt="">
                    <div class="overlay">
                        <h3>Diseño creativo</h3>
                        <p>Fotografia</p>
                    </div>
                </div>

                <div class="proyecto">
                    <img src="../datos_plantillas/template1/img/p4.jpg" alt="">
                    <div class="overlay">
                        <h3>Diseño creativo</h3>
                        <p>Fotografia</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pie de pagina  -->
    <footer>
        <a href="#inicio" class="arriba">
            <i class="fa-solid fa-angles-up"></i>
        </a>
    </footer>

    <script src="../datos_plantillas/template1/script.js"></script>

</body>

</html>