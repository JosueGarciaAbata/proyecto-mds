$(document).ready(function () {
  $(".nav-link").click(function (event) {
    event.preventDefault();
    console.log("Clic");

    $("#submenu-portafolio").slideDown();
  });

  $("#submenu-portafolio .crear-proyecto").click(function () {
    $("#formulario-portafolio").slideDown();
  });
});

function generarPaginaHTML() {
  var nombre = $("#nombre").val();
  var descripcion = $("#descripcion").val();
  var fechaInicio = $("#fecha_inicio").val();
  var fechaFin = $("#fecha_fin").val();

  $.post(
    "../../templates/generar_pagina.php",
    {
      nombre: nombre,
      descripcion: descripcion,
      fecha_inicio: fechaInicio,
      fecha_fin: fechaFin,
    },
    function (respuesta) {
      window.location.href =
        "../../templates/pagina_generada.php?html=" +
        encodeURIComponent(respuesta);
    }
  );
}
