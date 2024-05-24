/*
Filtro en función de los post
*/

$(document).ready(function () {
  $("#tipo-filtro").change(function () {
    // Elimina cualquier cosa anterior hecha
    $("#tipo-filtro-post").off("change");
    var typeSelected = $("#tipo-filtro").val();

    if (typeSelected === "tipo-posts") {
      $("#projects-div").hide();
      $("#portfolios-div").hide();
      // Mostrar los tipos de los posts
      $("#posts-div").show();

      // Obtener el tipo que ha seleccionado
      $("#tipo-filtro-post").change(function () {
        var tipoPosts = $("#tipo-filtro-post").val();
        filtroPosts(tipoPosts);
      });
    }

    if (typeSelected === "tipo-projects") {
      $("#posts-div").hide();
      $("#portfolios-div").hide();
      // Mostrar los proyectos
      $("#projects-div").show();

      // Obtener el tipo que ha seleccionado
      $("#tipo-filtro-projects").change(function () {
        var tipoProjects = $("#tipo-filtro-projects").val();
        filtroProjects(tipoProjects);
      });
    }

    if (typeSelected === "tipo-portfolios") {
      $("#posts-div").hide();
      $("#projects-div").hide();
      // Mostrar los portafolios
      $("#portfolios-div").show();

      filtroPortfolios();
    }
  });
});

function filtroPosts(tipoPosts) {
  $(
    "#categorias-posts-div, #categorias-tags-posts-div, #etiquetas-posts-div"
  ).hide();

  if (tipoPosts === "tipo-categoria") {
    $("#categorias-posts-div").show();
    getCategories("categories", "getCategories");

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
                action: "filterPostsByCategoriesVisitor",
              },
              success: function (response) {
                console.log(response);
                var pageWrapper = $(".row-cards");
                var data = JSON.parse(response);

                pageWrapper.empty();

                //Generar los contenedores con la informacion del post
                data.forEach(function (post) {
                  var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                  var card = $(
                    '<div class="card card-sm position-relative"></div>'
                  );

                  // SVG de comentar al extremo derecho del cardBody
                  var comments = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">' +
                      '<path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>' +
                      "</svg>"
                  );
                  comments.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

                  var anchor = $('<a href="#" class="d-block"></a>');

                  //Cargar imagen del post o generica

                  var imageUrl =
                    post.ubicacion_imagen_post != null &&
                    post.ubicacion_imagen_post.trim() !== ""
                      ? post.ubicacion_imagen_post
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
                      post.titulo_post +
                      "</h4>"
                  );

                  // Asignar el id del  post al ícono de comentar
                  comments.attr("data-id", post.id_post);

                  // Adjuntar elementos al DOM para formar la estructura del post
                  cardBody.append(title);
                  cardBody.append(comments); // Adjuntar el SVG de editar al extremo derecho del cardBody
                  card.append(anchor);
                  card.append(cardBody);
                  cardCol.append(card);

                  // Agregar la tarjeta al contenedor principal (pageWrapper)
                  pageWrapper.append(cardCol);

                  //Generar funcionalidad al boton de comentar
                  comments.on("click", function () {
                    // Comenetarios...
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

  if (tipoPosts === "tipo-etiqueta") {
    $("#categorias-tags-posts-div").show();
    getCategories("categories-tags", "getCategories");
    var selectedCategory = null;

    // Eliminar cualquier controlador de eventos previamente adjuntado
    $("#categories-tags").off("change", 'input[type="checkbox"]');

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

            var formSelectgroup = document.querySelector(
              "#etiquetas-posts-div"
            );

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
        $("#etiquetas-posts-div").show();
      } else {
        $("#etiquetas-posts-div").hide();
      }

      $("#btn_filter")
        .off()
        .click(function () {
          selectedTags = obtenerSeleccionados("etiquetas-posts-div");
          console.log(selectedTags);
          console.log(selectedCategory);

          if (selectedCategory.length > 0) {
            if (selectedTags.length > 0) {
              $.ajax({
                url: "procesarInformacion/filters/filters.php",
                type: "POST",
                data: {
                  categoryData: selectedCategory,
                  tagsData: selectedTags,
                  action: "filterPostsByTagsVisitor",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  pageWrapper.empty();

                  var data = JSON.parse(response);

                  data.forEach(function (post) {
                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

                    var comments = $(
                      '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>' +
                        "</svg>"
                    );
                    comments.css("cursor", "pointer");

                    var anchor = $('<a href="#" class="d-block"></a>');

                    var imageUrl =
                      post.ubicacion_imagen_post &&
                      post.ubicacion_imagen_post.trim() !== ""
                        ? post.ubicacion_imagen_post
                        : "../img/genericImagePost.jpg";

                    var imageElement = $("<img>");
                    imageElement.attr("src", imageUrl);
                    imageElement.addClass("card-img-top");

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
                        post.titulo_post +
                        "</h4>"
                    );

                    comments.attr("data-id", post.id_post);

                    cardBody.append(title);
                    cardBody.append(comments);
                    card.append(anchor);
                    card.append(cardBody);
                    cardCol.append(card);

                    // Agregar la tarjeta al contenedor principal (pageWrapper)
                    pageWrapper.append(cardCol);
                    //Generar funcionalidad al boton de comentar
                    comments.on("click", function () {
                      // Comenetarios...
                    });
                  });
                },
                error: function (xhr, status, error) {
                  console.log(error);
                },
              });
            } else {
              mostrarModalDeAdvertencia("Seleccione al menos una etiqueta");
            }
          } else {
            mostrarModalDeAdvertencia("Seleccione al menos una categoria");
          }
        });
    });
  }
}

function filtroProjects(tipoProjects) {
  $(
    "#categorias-projects-div, #categorias-tags-projects-div, #etiquetas-projects-div"
  ).hide();

  if (tipoProjects === "tipo-categoria") {
    $("#categorias-projects-div").show();
    getCategories("categories-projects", "getCategories");

    // Cambio en las categorias
    $("#categories-projects").on(
      "change",
      "input[type='checkbox']",
      function () {
        var selectedCategories = obtenerSeleccionados("categories-projects");
        console.log(selectedCategories);

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
                  action: "filterProjectsByCategoriesVisitor",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  var data = JSON.parse(response);

                  pageWrapper.empty();

                  //Generar los contenedores con la informacion del post
                  data.forEach(function (post) {
                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

                    // SVG de comentar al extremo derecho del cardBody
                    var comments = $(
                      '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">' +
                        '<path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>' +
                        "</svg>"
                    );
                    comments.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

                    var anchor = $('<a href="#" class="d-block"></a>');

                    //Cargar imagen del post o generica

                    var imageUrl =
                      post.ubicacion_imagen_post != null &&
                      post.ubicacion_imagen_post.trim() !== ""
                        ? post.ubicacion_imagen_post
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
                        post.titulo_proyecto +
                        "</h4>"
                    );

                    // Asignar el id del  post al ícono de comentar
                    comments.attr("data-id", post.id_proyecto);

                    // Adjuntar elementos al DOM para formar la estructura del post
                    cardBody.append(title);
                    cardBody.append(comments); // Adjuntar el SVG de editar al extremo derecho del cardBody
                    card.append(anchor);
                    card.append(cardBody);
                    cardCol.append(card);

                    // Agregar la tarjeta al contenedor principal (pageWrapper)
                    pageWrapper.append(cardCol);

                    //Generar funcionalidad al boton de comentar
                    comments.on("click", function () {
                      // Comenetarios...
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
      }
    );
  }

  if (tipoProjects === "tipo-etiqueta") {
    $("#categorias-tags-projects-div").show();
    getCategories("categories-tags-projects", "getCategories");
    var selectedCategory = null;

    // Eliminar cualquier controlador de eventos previamente adjuntado
    $("#categories-tags-projects").off("change", 'input[type="checkbox"]');

    $("#categories-tags-projects").on(
      "change",
      'input[type="checkbox"]',
      function () {
        var selectedTags = [];
        $('input[type="checkbox"]').not(this).prop("checked", false);

        selectedCategory = obtenerSeleccionados("categories-tags-projects");
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

              var formSelectgroup = document.querySelector(
                "#etiquetas-projects-div"
              );

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
          $("#etiquetas-projects-div").show();
        } else {
          $("#etiquetas-projects-div").hide();
        }

        $("#btn_filter")
          .off()
          .click(function () {
            selectedTags = obtenerSeleccionados("etiquetas-projects-div");
            console.log(selectedTags);
            console.log(selectedCategory);

            if (selectedCategory.length > 0) {
              if (selectedTags.length > 0) {
                $.ajax({
                  url: "procesarInformacion/filters/filters.php",
                  type: "POST",
                  data: {
                    categoryData: selectedCategory,
                    tagsData: selectedTags,
                    action: "filterProjectsByTagsVisitor",
                  },
                  success: function (response) {
                    console.log(response);
                    var pageWrapper = $(".row-cards");
                    pageWrapper.empty();

                    var data = JSON.parse(response);

                    data.forEach(function (post) {
                      var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                      var card = $(
                        '<div class="card card-sm position-relative"></div>'
                      );

                      var comments = $(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">' +
                          '<path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/>' +
                          "</svg>"
                      );
                      comments.css("cursor", "pointer");

                      var anchor = $('<a href="#" class="d-block"></a>');

                      var imageUrl =
                        post.ubicacion_imagen_post &&
                        post.ubicacion_imagen_post.trim() !== ""
                          ? post.ubicacion_imagen_post
                          : "../img/genericImagePost.jpg";

                      var imageElement = $("<img>");
                      imageElement.attr("src", imageUrl);
                      imageElement.addClass("card-img-top");

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
                          post.titulo_proyecto +
                          "</h4>"
                      );

                      comments.attr("data-id", post.id_proyecto);

                      cardBody.append(title);
                      cardBody.append(comments);
                      card.append(anchor);
                      card.append(cardBody);
                      cardCol.append(card);

                      // Agregar la tarjeta al contenedor principal (pageWrapper)
                      pageWrapper.append(cardCol);
                      //Generar funcionalidad al boton de comentar
                      comments.on("click", function () {
                        // Comenetarios...
                      });
                    });
                  },
                  error: function (xhr, status, error) {
                    console.log(error);
                  },
                });
              } else {
                mostrarModalDeAdvertencia("Seleccione al menos una etiqueta");
              }
            } else {
              mostrarModalDeAdvertencia("Seleccione al menos una categoria");
            }
          });
      }
    );
  }
}

function filtroPortfolios() {
  $("#skills-div").show();
  getSkills("skills", "getSkillsPortfolios");

  // Clic en el boton de filtrar
  $("#btn_filter").click(function () {
    var selectedSkills = obtenerSeleccionados("skills");

    // Si no hay habilidades seleccionadas, muestra el mensaje de advertencia
    if (selectedSkills.length === 0) {
      mostrarModalDeAdvertencia("Seleccione una habilidad");
    } else {
      // Logica para poner los portafolios en la página
      console.log("Selected skills > 0");
      $.ajax({
        url: "procesarInformacion/filters/filters.php",
        type: "POST",
        data: {
          skillsData: selectedSkills,
          action: "filterPortfoliosBySkillsVisitor",
        },
        success: function (response) {
          console.log("Respuesta");
          console.log(response);
        },
        error: function (xhr, status, error) {
          console.log(error);
        },
      });
    }
  });
}

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
