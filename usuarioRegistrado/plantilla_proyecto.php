<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de la página</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="bg-dark py-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <a class="navbar-brand" href="#">Mi Sitio Web</a>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Acerca de</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- PanelHero -->
    <section id="panelHero" class="bg-primary py-5 text-center text-light">
        <div class="container">
            <h1>¡Hola, soy un héroe!</h1>
            <!-- Formulario para ingresar el Título del proyecto -->
            <form id="projectTitleForm">
                <div class="mb-3">
                    <label for="projectTitle" class="form-label">Título del proyecto</label>
                    <input type="text" class="form-control" id="projectTitle" name="projectTitle"
                        placeholder="Ingresa el título del proyecto...">
                </div>
                <button type="submit" class="btn btn-light">Guardar</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark py-4 text-light text-center">
        <div class="container">
            <p>&copy; 2024 Mi Sitio Web</p>
        </div>
    </footer>

    <!-- Enlace a Bootstrap Bundle (que incluye Popper.js) y Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>