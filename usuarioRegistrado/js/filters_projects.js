/*
Filtro en función de los proyectos. Usuario registrado
*/

$(document).ready(function () {
  $("#tipo-filtro-project").change(function () {
    var typeSelected = $("#tipo-filtro-project").val();
    $("#categorias-div, #categorias-tags-div, #etiquetas-div").hide();

    if (typeSelected === "tipo-categoria") {
      $("#categorias-div").show();
      getCategories("categories", "getCategoriesPosts");

      // Cambio en las categorias
      $("#categories").on("change", "input[type='checkbox']", function () {
        var selectedCategories = obtenerSeleccionados("categories");

        $("#btn_filter")
          .off()
          .click(function () {
            if (selectedCategories.length > 0) {
              // Enviando solicitud para las categorias
              $.ajax({
                url: "procesarInformacion/filters/filters.php",
                type: "POST",
                data: {
                  categoriesData: selectedCategories,
                  action: "filterPostsByCategoriesProjects",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  var data = JSON.parse(response);

                  pageWrapper.empty();

                  //Generar los contenedores con la informacion del proyecto
                  data.forEach(function (project) {
                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

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
                      height: "100%",
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
                      console.log(
                        "Editar project con ID:",
                        project.id_proyecto
                      );

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
                          $("#title_project").val(
                            projectInfo["titulo_proyecto"]
                          );
                          $("#content_project").val(
                            projectInfo["descripcion_proyecto"]
                          );
                          $("#fecha_inicio").val(
                            projectInfo["fecha_inicio_proyecto"]
                          );
                          $("#fecha_fin").val(
                            projectInfo["fecha_finalizacion_proyecto"]
                          );
                          $("#state").val(projectInfo["id_estado_proyecto"]);

                          // Actualizar la imagen del proyecto
                          if (
                            projectInfo["ubicacion_imagen_proyecto"] !== null
                          ) {
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
                          $("#categoria").val(
                            projectInfo["id_categoria_proyecto"]
                          );

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
                            etiquetasCategoria.forEach(function (
                              etiquetaCategoria
                            ) {
                              var labelElement =
                                document.createElement("label");
                              labelElement.classList.add(
                                "form-selectgroup-item"
                              );

                              var inputElement =
                                document.createElement("input");
                              inputElement.type = "checkbox";
                              inputElement.name = "name";
                              inputElement.value =
                                etiquetaCategoria.nombre_etiqueta;
                              inputElement.dataset.id =
                                etiquetaCategoria.id_etiqueta;
                              inputElement.classList.add(
                                "form-selectgroup-input"
                              );

                              var spanElement = document.createElement("span");
                              spanElement.classList.add(
                                "form-selectgroup-label"
                              );
                              spanElement.textContent =
                                etiquetaCategoria.nombre_etiqueta;

                              labelElement.appendChild(inputElement);
                              labelElement.appendChild(spanElement);

                              // Verificar si esta etiqueta está asociada al proyecto actual y marcar el checkbox correspondiente
                              var etiquetaAsociada = etiquetas.find(function (
                                etiqueta
                              ) {
                                return (
                                  etiqueta.id_etiqueta ===
                                  etiquetaCategoria.id_etiqueta
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
                          $("#btn_project").attr(
                            "data_id_project",
                            project.id_proyecto
                          );
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
                },
              });
            } else {
              mostrarModalDeAdvertencia("Seleccione al menos una categoría");
            }
          });
      });
    }

    if (typeSelected === "tipo-etiqueta") {
      $("#categorias-tags-div").show();
      getCategories("categories-tags", "getCategoriesPosts");
      var selectedCategory = null;

      $("#categories-tags").on("change", 'input[type="checkbox"]', function () {
        var selectedTags = [];
        $('input[type="checkbox"]').not(this).prop("checked", false);

        selectedCategory = obtenerSeleccionados("categories-tags");
        if (selectedCategory.length > 0) {
          var categoryId = $(this).data("id");
          $.ajax({
            url: "procesarInformacion/filters/filters.php",
            type: "POST",
            data: {
              action: "getOneCategoryTagsPost",
              categoryData: categoryId,
            },
            success: function (response) {
              console.log(response);
              var labels = JSON.parse(response);

              var formSelectgroup = document.querySelector("#etiquetas-div");

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
              console.log(error);
            },
          });
          $("#etiquetas-div").show();
        } else {
          $("#etiquetas-div").hide();
        }

        $("#btn_filter")
          .off()
          .click(function () {
            selectedTags = obtenerSeleccionados("etiquetas-div");
            console.log(selectedTags);
            console.log(selectedCategory);

            if (selectedCategory.length > 0 && selectedTags.length > 0) {
              $.ajax({
                url: "procesarInformacion/filters/filters.php",
                type: "POST",
                data: {
                  categoryData: selectedCategory,
                  tagsData: selectedTags,
                  action: "filterPostsByTagsProjects",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  pageWrapper.empty();

                  var data = JSON.parse(response);

                  //Generar los contenedores con la informacion del proyecto
                  data.forEach(function (project) {
                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

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
                      height: "100%",
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
                      console.log(
                        "Editar project con ID:",
                        project.id_proyecto
                      );

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
                          $("#title_project").val(
                            projectInfo["titulo_proyecto"]
                          );
                          $("#content_project").val(
                            projectInfo["descripcion_proyecto"]
                          );
                          $("#fecha_inicio").val(
                            projectInfo["fecha_inicio_proyecto"]
                          );
                          $("#fecha_fin").val(
                            projectInfo["fecha_finalizacion_proyecto"]
                          );
                          $("#state").val(projectInfo["id_estado_proyecto"]);

                          // Actualizar la imagen del proyecto
                          if (
                            projectInfo["ubicacion_imagen_proyecto"] !== null
                          ) {
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
                          $("#categoria").val(
                            projectInfo["id_categoria_proyecto"]
                          );

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
                            etiquetasCategoria.forEach(function (
                              etiquetaCategoria
                            ) {
                              var labelElement =
                                document.createElement("label");
                              labelElement.classList.add(
                                "form-selectgroup-item"
                              );

                              var inputElement =
                                document.createElement("input");
                              inputElement.type = "checkbox";
                              inputElement.name = "name";
                              inputElement.value =
                                etiquetaCategoria.nombre_etiqueta;
                              inputElement.dataset.id =
                                etiquetaCategoria.id_etiqueta;
                              inputElement.classList.add(
                                "form-selectgroup-input"
                              );

                              var spanElement = document.createElement("span");
                              spanElement.classList.add(
                                "form-selectgroup-label"
                              );
                              spanElement.textContent =
                                etiquetaCategoria.nombre_etiqueta;

                              labelElement.appendChild(inputElement);
                              labelElement.appendChild(spanElement);

                              // Verificar si esta etiqueta está asociada al proyecto actual y marcar el checkbox correspondiente
                              var etiquetaAsociada = etiquetas.find(function (
                                etiqueta
                              ) {
                                return (
                                  etiqueta.id_etiqueta ===
                                  etiquetaCategoria.id_etiqueta
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
                          $("#btn_project").attr(
                            "data_id_project",
                            project.id_proyecto
                          );
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
                },
              });
            } else {
              mostrarModalDeAdvertencia("Ingrese una categoria o tag");
            }
          });
      });
    }
  });
});

/*
Filtro en función de los post: btn_projects. Usuario registrado
*/
$(document).ready(function () {});

function obtenerSeleccionados(contenedor) {
  return $("#" + contenedor + " input[type='checkbox']:checked")
    .map(function () {
      return $(this).data("id");
    })
    .get();
}

function getCategories(tipoContenedor, tipoAccion) {
  $.ajax({
    url: "procesarInformacion/filters/filters.php",
    type: "POST",
    data: {
      action: tipoAccion,
    },
    success: function (response) {
      var labels = JSON.parse(response);
      console.log(labels);

      var formSelectgroup = document.querySelector("#" + tipoContenedor);

      formSelectgroup.innerHTML = "";

      labels.forEach(function (label) {
        var labelElement = document.createElement("label");
        labelElement.classList.add("form-selectgroup-item");

        var inputElement = document.createElement("input");
        inputElement.type = "checkbox";
        inputElement.name = "name";
        inputElement.value = label.nombre_categoria;
        inputElement.dataset.id = label.id_categoria;

        inputElement.classList.add("form-selectgroup-input");

        var spanElement = document.createElement("span");
        spanElement.classList.add("form-selectgroup-label");
        spanElement.textContent = label.nombre_categoria;

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
