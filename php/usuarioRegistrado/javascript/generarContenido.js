$(document).ready(function () {
  // Muestra los portafolios
  $(".portfolio-link").click(function (event) {
    event.preventDefault();
    $("#submenu-portafolio").slideDown();
    $("#portafolios-section").slideDown();
  });

  // Oculta los portafolios
  $(".nav-link")
    .not(".portfolio-link")
    .click(function (event) {
      event.preventDefault();
      $("#submenu-portafolio").slideUp();
      $("#portafolios-section").slideUp();
    });

  // Obtiene los portafolios
  $.ajax({
    url: "crud_portafolio/obtener_portafolios.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      // Verifica si hay proyectos para mostrar
      if (data.length > 0) {
        // Si hay proyectos, itera sobre los datos y agrega dinámicamente los portafolios a la galería
        $.each(data, function (index, portafolio) {
          var proyectoHTML =
            '<div class="portafolio">' +
            '<img src="../../templates/datos_plantillas/template1/img/p1.jpg" alt="">' +
            '<div class="overlay">' +
            "<h3>" +
            portafolio.nombre_apellido +
            "</h3>" +
            "<p>" +
            portafolio.sobre_mi +
            "</p>" +
            "</div>" +
            "</div>";
          $(".galeria").append(proyectoHTML);
        });
      } else {
        // Si no hay proyectos, muestra el mensaje correspondiente
        $(".galeria").html(
          '<h2 class="no-proyectos-message">No tienes proyectos para visualizar</h2>'
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener los proyectos:", error, xhr, status);
    },
  });
});
