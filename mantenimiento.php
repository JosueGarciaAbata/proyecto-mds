<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>En Mantenimiento</title>
    <style>
      body {
        margin: 0;
        padding: 0;
        background-image: url("mantenimiento.jpg");
        background-size: cover;
        background-position: center;
        text-align: center;
        display: flex;
        justify-content: baseline;
        align-items: flex-end;
        height: 100vh;
      }
      .container {
        padding: 20px;
        border-radius: 10px;
        color: #ffffff;
        font-weight: bold;
        transition: background-color 2s;
      }
      h1{
        font-family: cursive;
        font-size:20px;
        color:crimson;
        margin-bottom: 20px;
      }
      p{
        font-family: cursive;
        color:#fff;
        font-size: 12px;
      }
      .container img {
        max-width: 100%; /* La imagen no excederá el ancho del contenedor */
        height: auto; /* La altura se ajustará automáticamente */
        display: block; /* Asegura que la imagen no tenga margen adicional */
        margin: 0 auto; /* Centra la imagen horizontalmente */
      }
    </style>
  </head>
  <body>
    <div class="container" id="container">
      <img src="https://es.dreamstime.com/icono-de-env%C3%ADo-correo-flecha-vectores-el-mejor-para-enviar-correos-electr%C3%B3nicos-image239366823" alt="">
      <h1>¡Estamos en Mantenimiento!</h1>
      <p>
        Disculpa las molestias, pero estamos realizando algunas actualizaciones
        en nuestro sitio web.
      </p>
      <p>Volveremos pronto. Gracias por tu paciencia.</p>
    </div>

    <script>
      const container = document.getElementById('container');
      const colors = ['#6a0080', '#8B31C1']; 
      let currentIndex = 0;

      setInterval(() => {
        container.style.backgroundColor = colors[currentIndex];
        currentIndex = (currentIndex + 1) % colors.length;
      }, 2000);
    </script>
  </body>
</html>