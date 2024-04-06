<footer class="footer footer-transparent d-print-none">
  <div class="container-xl">
    <div class="row text-center align-items-center flex-row-reverse">
      <div class="col-lg-auto ms-lg-auto">

      </div>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            Copyright &copy; 2024
            <a href="." class="link-secondary">My Creative Portfolio</a>.
            All rights reserved.
          </li>
          <li class="list-inline-item">
            <a href="./changelog.html" class="link-secondary" rel="noopener">
              v1.0.0-beta
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Libs JS -->
<script src="./../dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
<script src="./../dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
<script src="./../dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
<script src="./../dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
<!-- Tabler Core -->
<script src="./../dist/js/tabler.min.js?1684106062" defer></script>
<script src="./../dist/js/demo.min.js?1684106062" defer></script>

<script src="./../dist/libs/jquery_3.2.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
<script scr="../../../js/options.js"></script>

<script>
  $(document).ready(function () {
    $(".toggle-submenu").click(function () {
      $("#formulario-proyecto").slideUp();
      $("#submenu-proyectos").toggle();
    });

    $(".crear-proyecto").click(function () {
      $("#formulario-proyecto").slideDown();
    });

    $("#formulario").submit(function (event) {
      event.preventDefault();

      $("#confirmacion").slideDown();
    });

    $("#confirmar").click(function () {
      $("#formulario").submit();
      generarPaginaHTML();
      // Ocultar el mensaje de confirmación
      $("#confirmacion").slideUp();
    });

    $("#cancelar").click(function () {
      $("#confirmacion").slideUp();
    });
  });

  function generarPaginaHTML() {
    var nombre = $("#nombre").val();
    var descripcion = $("#descripcion").val();
    var fechaInicio = $("#fecha_inicio").val();
    var fechaFin = $("#fecha_fin").val();

    // Enviar los datos al servidor para generar la página HTML basada en la plantilla
    $.post("../../plantillas/generar_pagina.php", {
      nombre: nombre,
      descripcion: descripcion,
      fecha_inicio: fechaInicio,
      fecha_fin: fechaFin
    }, function (respuesta) {
      window.location.href = '../../plantillas/pagina_generada.php?html=' + encodeURIComponent(respuesta);
    });
  }

</script>

</body>

</html>