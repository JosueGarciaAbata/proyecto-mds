function generarPaginaHTML() {
  var tituloProyecto = $("#titulo-proyecto").val();
  var mensajeBienvenida = $("#mensaje-bienvenida").val();
  var nombres = $("#nombre").val();
  var apellidos = $("#apellidos").val();
  var habilidadesTecnicas = $("#habilidades-tecnicas").val();
  var habilidadesSociales = $("#habilidades-sociales").val();

  // En este punto, los datos se deben enviar a la base de datos.
  $.post(
    "../../templates/generar_pagina.php",
    {
      tituloProyecto: tituloProyecto,
      mensajeBienvenida: mensajeBienvenida,
      nombres: nombres,
      apellidos: apellidos,
      habilidadesTecnicas: habilidadesTecnicas,
      habilidadesSociales: habilidadesSociales,
    },
    function (respuesta) {
      console.log("Enviando...");
    }
  );
}
