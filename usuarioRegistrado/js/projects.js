/**
 *
 *Darle funcionalidad al modal de eliminacion
 *
 */

$(document).ready(function () {
  $('#deleteModal .close, #deleteModal button[data-dismiss="modal"]').click(
    function () {
      $("#deleteModal").modal("hide");
    }
  );
});
/**
 *
 *
 *Cargar  los proyectos del usuario
 *
 *
 */
$(document).ready(function () {
  var pageWrapper = $(".row-cards");

  function getprojects() {
    $.ajax({
      url: "procesarInformacion/projects/projects.php",
      type: "POST",
      data: {
        action: "selectProjects",
      },
      success: function (response) {
        var data = JSON.parse(response);
        console.log(data);

        pageWrapper.empty();

        data.forEach(function (project) {
          var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
          var card = $('<div class="card card-sm position-relative"></div>');

          // SVG de borrado en la esquina superior derecha
          var deleteIcon = $(
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon delete-icon position-absolute top-0 end-0 m-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="top: -10px;"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'
          );

          // SVG de editar al extremo derecho del cardBody
          var editIcon = $(
            '<svg xmlns="http://www.w3.org/2000/svg" class="icon edit-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line></svg>'
          );
          editIcon.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

          var anchor = $('<a href="#" class="d-block"></a>');
          //Cargar imagen del proyecto o generica
          var imageUrl =
            project.ubicacion_imagen_proyecto != null &&
            project.ubicacion_imagen_proyecto.trim() !== ""
              ? project.ubicacion_imagen_proyecto
              : "../img/genericImagePost.jpg";

          //Asignar imagen

          var imageElement = $("<img>");
          imageElement.attr("src", imageUrl);
          imageElement.addClass("card-img-top");

          //Establecer propiedades de la imagen

          imageElement.css({
            width: "100%",
            "object-fit": "cover",
            "max-height": "200px",
          });

          anchor.append(imageElement);
          var cardBody = $(
            '<div class="card-body d-flex align-items-center justify-content-between"></div>'
          );
          var title = $(
            '<h4 class="mb-0" style="margin-left: 10px;">' +
              project.titulo_proyecto +
              "</h4>"
          );

          // Asignar el id del proyecto al ícono de editar y al ícono de borrar
          editIcon.attr("data-id", project.id_proyecto);
          deleteIcon.attr("data-id", project.id_proyecto);

          // Adjuntar elementos al DOM para formar la estructura del project
          cardBody.append(title);
          cardBody.append(editIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody
          card.append(deleteIcon); // Adjuntar el SVG de borrado a la tarjeta
          card.append(anchor);
          card.append(cardBody);
          cardCol.append(card);

          // Agregar la tarjeta al contenedor principal (pageWrapper)
          pageWrapper.append(cardCol);

          //Generar funcionalidad al boton de borrado del proyecto

          deleteIcon.on("click", function () {
            $("#deleteModal").modal("show");

            // Configurar el botón de confirmar borrado
            $("#confirmDeleteBtn")
              .off("click")
              .on("click", function () {
                console.log(
                  "Borrando el project con ID: " + project.id_proyecto
                );
                $.ajax({
                  url: "procesarInformacion/projects/projects.php",
                  type: "POST",
                  data: {
                    id_project: project.id_proyecto,
                    action: "deleteProject",
                  },
                  success: function (response) {
                    if (response == "true") {
                      mostrarModalExito("project Eliminado");
                      setTimeout(function () {
                        window.location.href = "projects.php";
                      }, 2500);
                    } else {
                      mostrarModalDeAdvertencia(
                        "The project could not be deleted"
                      );
                    }
                  },
                  error: function (xhr, status, error) {
                    console.error(error);
                    mostrarModalDeAdvertencia("Connection error");
                  },
                });

                $("#confirmModal").modal("hide");
              });
          });

          //Generar funcionalidad al boton de edicion

          editIcon.on("click", function () {
            console.log("Editar project con ID:", project.id_proyecto);

            $.ajax({
              url: "procesarInformacion/projects/projects.php",
              type: "POST",
              data: {
                action: "getInfoUpdateProject",
                id_project: project.id_proyecto,
              },
              success: function (response) {
                console.log(data);
                var data = JSON.parse(response);

                // Acceder a la información del proyecto
                var projectInfo = data["project_info"];
                console.log(data);

                // Acceder a las etiquetas asociadas al proyecto
                var labels = data["etiquetas"];

                // Rellenar los campos con la información del proyecto
                $("#title_project").val(projectInfo["titulo_proyecto"]);
                $("#content_project").val(projectInfo["descripcion_proyecto"]);
                $("#fecha_inicio").val(projectInfo["fecha_inicio_proyecto"]);
                $("#fecha_fin").val(projectInfo["fecha_finalizacion_proyecto"]);
                $("#state").val(projectInfo["id_estado_proyecto"]);

                // Actualizar la imagen del proyecto
                if (projectInfo["ubicacion_imagen_proyecto"] !== null) {
                  $("#projectImage").attr(
                    "src",
                    projectInfo["ubicacion_imagen_proyecto"]
                  );
                } else {
                  $("#projectImage").attr(
                    "src",
                    "../img/genericUploadImage.jpg"
                  );
                }

                // Rellenar la categoría del proyecto
                $("#categoria").val(projectInfo["id_categoria_proyecto"]);

                // Modificar el título superior
                $("#superiorTitle").text("Editar project");

                $("#imageModal").modal("show");
                // Recuperar las etiquetas asociadas al proyecto
                var etiquetas = data["etiquetas"];

                //Esta seccion del codigo permite que se muestren activas las etiquetas asociadas al proyecto

                // Recuperar las etiquetas de la categoría del proyecto

                var etiquetasCategoria = data["etiquetas_categoria"];
                var formSelectgroup =
                  document.querySelector(".form-selectgroup");
                if (etiquetas.length > 0) {
                  formSelectgroup.innerHTML = "";
                  etiquetasCategoria.forEach(function (etiquetaCategoria) {
                    var labelElement = document.createElement("label");
                    labelElement.classList.add("form-selectgroup-item");

                    var inputElement = document.createElement("input");
                    inputElement.type = "checkbox";
                    inputElement.name = "name";
                    inputElement.value = etiquetaCategoria.nombre_etiqueta;
                    inputElement.dataset.id = etiquetaCategoria.id_etiqueta;
                    inputElement.classList.add("form-selectgroup-input");

                    var spanElement = document.createElement("span");
                    spanElement.classList.add("form-selectgroup-label");
                    spanElement.textContent = etiquetaCategoria.nombre_etiqueta;

                    labelElement.appendChild(inputElement);
                    labelElement.appendChild(spanElement);

                    // Verificar si esta etiqueta está asociada al proyecto actual y marcar el checkbox correspondiente
                    var etiquetaAsociada = etiquetas.find(function (etiqueta) {
                      return (
                        etiqueta.id_etiqueta === etiquetaCategoria.id_etiqueta
                      );
                    });

                    if (etiquetaAsociada) {
                      inputElement.checked = true;
                    }

                    formSelectgroup.appendChild(labelElement);
                  });
                }

                //Esta seccion permite al modal saber que lo que se realizara sera una actualizacion

                $("#btn_project").attr("data_info", "update");
                $("#btn_project").attr("data_id_project", project.id_proyecto);
              },
              error: function (xhr, status, error) {
                console.error("Error al obtener el project:", error);
              },
            });
          });
        });
      },
      error: function (xhr, status, error) {
        console.log(error);
        mostrarModalDeAdvertencia("An error has occurred");
      },
    });
  }

  //Obtener los proyectos

  getprojects();
});

/*
 *
 *
 *Validar informacion ingresada
 *
 *
 */
$(document).ready(function () {
  $.validator.addMethod(
    "greaterThan",
    function (value, element, param) {
      var startDate = $(param).val();
      return startDate === "" || new Date(value) > new Date(startDate);
    },
    "Please enter a date after the start date"
  );
  $("#projectForm").validate({
    rules: {
      title_project: {
        required: true,
        minlength: 10,
        maxlength: 50,
      },
      categoria: {
        required: true,
      },
      state: {
        required: true,
      },
      content_project: {
        required: true,
        minlength: 10,
        maxlength: 350,
      },
      fecha_inicio: {
        required: true,
      },
      fecha_fin: {
        required: true,
        greaterThan: "#fecha_inicio", // Agregamos una regla personalizada para verificar si la fecha_fin es mayor que la fecha_inicio
      },
    },
    messages: {
      title_project: {
        required: "Please enter a name",
        minlength: "Please enter at least 10 characters",
        maxlength: "The maximum number of characters for the title is 50",
      },
      categoria: {
        required: "Please select a category",
      },
      state: {
        required: "Please select the project visibility",
      },
      content_project: {
        required: "Please enter content for the project",
        minlength: "Please enter at least 10 characters",
        maxlength: "The maximum number of characters for the content is 350",
      },
      fecha_inicio: {
        required: "Please select a start date",
      },
      fecha_fin: {
        required: "Please select an end date",
        greaterThan: "The end date must be greater than the start date", // Mensaje de error personalizado para la regla greaterThan
      },
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("error");
      var container = $("<div>").addClass("error-container");
      container.insertAfter(element);
      error.appendTo(container);
    },
    highlight: function (element) {
      $(element).addClass("error");
    },
    unhighlight: function (element) {
      $(element).removeClass("error");
    },
  });
});

/*
 *
 *
 *Cambiar las etiquetas dependiendo de la categria
 *
 *
 */

$(document).ready(function () {
  $("#categoria").change(function () {
    var categoriaSeleccionada = $(this).val();
    console.log("Categoria seleccionada:", categoriaSeleccionada);

    $.ajax({
      url: "procesarInformacion/projects/projects.php",
      method: "POST",
      data: {
        action: "changeCategory",
        category: categoriaSeleccionada,
      },
      success: function (response) {
        var labels = JSON.parse(response);

        var formSelectgroup = document.querySelector(".form-selectgroup");

        formSelectgroup.innerHTML = "";

        labels.forEach(function (label) {
          var labelElement = document.createElement("label");
          labelElement.classList.add("form-selectgroup-item");

          var inputElement = document.createElement("input");
          inputElement.type = "checkbox";
          inputElement.name = "name";
          inputElement.value = label.nombre_etiqueta;
          inputElement.dataset.id = label.id_etiqueta;

          inputElement.classList.add("form-selectgroup-input");

          var spanElement = document.createElement("span");
          spanElement.classList.add("form-selectgroup-label");
          spanElement.textContent = label.nombre_etiqueta;

          labelElement.appendChild(inputElement);
          labelElement.appendChild(spanElement);

          formSelectgroup.appendChild(labelElement);
        });
      },
      error: function (xhr, status, error) {
        console.error(error);
      },
    });
  });
});

/*
 *
 *
 *Guardar la imagen ingresada en un form
 *
 *
 */

$("#projectInput").change(function () {
  $("#projectFormImage").submit();
});

/*
 *
 *
 * Cambiar dinamicamente la imagen del post
 *
 */

$("#projectFormImage").submit(function (event) {
  event.preventDefault();
  var formData = new FormData(this);
  formData.append("action", "imgProjectTemporal");

  $.ajax({
    url: "procesarInformacion/projects/projects.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);

      if (response == "false") {
        mostrarModalDeAdvertencia("Error loading image");
        $("#projectInput").val("");
      } else {
        var imageUrl = response;
        $("#projectImage").attr("src", imageUrl);
      }
    },
    error: function (xhr, status, error) {
      console.log(error);
      mostrarModalDeAdvertencia("An error has occurred");
    },
  });
});

//Esta seccion le permite saber al modal que se usara para crear un proyecto

$("#btn_create").click(function () {
  $("#btn_project").attr("data_info", "create");
  $("#superiorTitle").text("Crear project");

  if ($("#btn_project").attr("data_id_project") !== undefined) {
    $("#btn_project").removeAttr("data_id_project");
  }
});

/*
 *
 *
 *Procesar informacion del proyecto
 *
 *
 */

$("#btn_project").click(function () {
  //Recuperar accion a realizar creacion  o actualiacion
  var dataInfoValue = $(this).attr("data_info");
  //Recuperar imagen
  var imageFile = $("#projectInput")[0].files[0];

  if (
    $("#projectForm").valid() &&
    $("#categoria").val() !== null &&
    $("#state").val() != null
  ) {
    $("#btn_project").prop("disabled", true);
    //Guardar etiquetas seleccionadas
    var labelsActive = [];
    $(".form-selectgroup input[type='checkbox']:checked").each(function () {
      var labelId = $(this).data("id");
      labelsActive.push(labelId);
    });
    //Guardar informacion en formData
    var formData = new FormData();
    if (imageFile) {
      formData.append("projectImage", imageFile);
    }

    if (dataInfoValue == "create") {
      formData.append("action", "createNewProject");
    } else {
      formData.append("action", "updateProject");
      formData.append("id_project", $(this).attr("data_id_project"));
    }

    formData.append("title", $("#title_project").val());
    formData.append("content", $("#content_project").val());
    formData.append("id_category", $("#categoria").val());
    formData.append("state", $("#state").val());
    formData.append("date_start", $("#fecha_inicio").val());
    formData.append("date_end", $("#fecha_fin").val());
    formData.append("labelsActive", JSON.stringify(labelsActive));
    //Enviar informacion del proyecto al backend
    $.ajax({
      url: "procesarInformacion/projects/projects.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response);

        if (response == "true") {
          mostrarModalExito("Project Successfully Saved");
          setTimeout(function () {
            window.location.href = "projects.php";
          }, 2500);
        } else {
          mostrarModalDeAdvertencia("The project could not be saved");
          $("#btn_project").prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
        mostrarModalDeAdvertencia("Connection error");
        $("#btn_project").prop("disabled", false);
      },
    });
    //////////////
  } else {
    mostrarModalDeAdvertencia(
      "Necessary missing information or Incorrect information"
    );
  }
});
$(document).ready(function () {});
