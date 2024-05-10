/*
Filtro en funcion de los portafolios. Usuario registrado
*/

$(document).ready(function () {
  $("#tipo-filtro-portfolios").change(function () {
    var typeSelected = $("#tipo-filtro-portfolios").val();

    if (typeSelected === "tipo-skills") {
      $("#skills-div").show();
      getSkills("skills", "getSkillsPortfolios");

      // Cambio en las categorias
      $("#skills").on("change", "input[type='checkbox']", function () {
        var selectedSkills = obtenerSeleccionados("skills");

        $("#btn_filter")
          .off()
          .click(function () {
            // Aqui poner la logica para agregar los portafolios a la página.
            if (selectedSkills.length > 0) {
              $.ajax({
                url: "procesarInformacion/filters/filters.php",
                type: "POST",
                data: {
                  skillsData: selectedSkills,
                  action: "filterPostsBySkills",
                },
                success: function (response) {
                  console.log(response);
                },
                error: function (xhr, status, error) {
                  console.log(error);
                },
              });
            } else {
              mostrarModalDeAdvertencia("Seleccione una habilidad");
            }
          });
      });
    }
  });
});

function obtenerSeleccionados(contenedor) {
  return $("#" + contenedor + " input[type='checkbox']:checked")
    .map(function () {
      return $(this).data("id");
    })
    .get();
}

function getSkills(tipoContenedor, tipoAccion) {
  $.ajax({
    url: "procesarInformacion/filters/filters.php",
    type: "POST",
    data: {
      action: tipoAccion,
    },
    success: function (response) {
      console.log(response);
      var labels = JSON.parse(response);

      var formSelectgroup = document.querySelector("#" + tipoContenedor);

      formSelectgroup.innerHTML = "";

      labels.forEach(function (label) {
        var labelElement = document.createElement("label");
        labelElement.classList.add("form-selectgroup-item");

        var inputElement = document.createElement("input");
        inputElement.type = "checkbox";
        inputElement.name = "name";
        inputElement.value = label.nombre_habilidades;
        inputElement.dataset.id = label.id_habilidades;

        inputElement.classList.add("form-selectgroup-input");

        var spanElement = document.createElement("span");
        spanElement.classList.add("form-selectgroup-label");
        spanElement.textContent = label.nombre_habilidades;

        labelElement.appendChild(inputElement);
        labelElement.appendChild(spanElement);

        formSelectgroup.appendChild(labelElement);
      });
    },
    error: function (xhr, status, error) {
      console.log(error);
    },
  });
}

function mostrarModalDeAdvertencia(mensaje) {
  Swal.fire({
    icon: "warning",
    title: "Alerta",
    text: mensaje,
    confirmButtonText: "Aceptar",
  });
}

function mostrarModalExito(mensaje) {
  Swal.fire({
    position: "center",
    icon: "success",
    title: mensaje,
    showConfirmButton: false,
    timer: 2500,
  });
}
