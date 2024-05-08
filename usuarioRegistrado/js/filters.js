/*
Filtro en función de los post: btn_post
*/
$(document).ready(function () {
  $("#tipo-filtro-post").change(function () {
    var typeSelected = $(this).val();
    $("#categorias-div, #categorias-tags-div, #etiquetas-div").hide();

    if (typeSelected === "tipo-categoria") {
      $("#categorias-div").show();
      getCategories("categories", "getCategoriesPosts");
      manejarFiltro("categories");
    }

    if (typeSelected === "tipo-etiqueta") {
      $("#categorias-tags-div").show();
      getCategories("categories-tags", "getCategoriesPosts");
      manejarFiltro("categories-tags");
    }
  });

  function manejarFiltro(contenedor) {
    // Elimina todos los controladores de eventos previos
    $("#" + contenedor).off();

    var selectedCategories = [];
    var selectedTags = [];

    $("#" + contenedor).on("change", "input[type='checkbox']", function () {
      selectedCategories = obtenerSeleccionados(contenedor);

      if (contenedor === "categories-tags") {
        if (selectedCategories.length > 0) {
          $("#etiquetas-div").show();
          getTags(selectedCategories, "tags", "getTagsPosts");
          manejarFiltro("tags");
        } else {
          $("#etiquetas-div").hide();
          selectedTags = [];
        }
      }
    });

    $("#tags").on("change", "input[type='checkbox']", function () {
      selectedTags = obtenerSeleccionados("tags");
    });

    $("#btn_filter")
      .off() // Elimina el controlador de clic previo
      .click(function () {
        if (contenedor === "categories") {
          console.log(selectedCategories);

          if (selectedCategories.length > 0) {
            // Enviando solicitud para las categorias
            $.ajax({
              url: "procesarInformacion/filters/filters.php",
              type: "POST",
              data: {
                categoriesData: selectedCategories,
                action: "filterPostsByCategories",
              },
              success: function (response) {
                var pageWrapper = $(".row-cards");

                var data = JSON.parse(response);

                pageWrapper.empty();

                data.forEach(function (post) {
                  var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                  var card = $(
                    '<div class="card card-sm position-relative"></div>'
                  );

                  var deleteIcon = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon delete-icon position-absolute top-0 end-0 m-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="top: -10px;"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'
                  );

                  var editIcon = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon edit-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line></svg>'
                  );
                  editIcon.css("cursor", "pointer");

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

                  editIcon.attr("data-id", post.id_post);
                  deleteIcon.attr("data-id", post.id_post);

                  cardBody.append(title);
                  cardBody.append(editIcon);
                  card.append(deleteIcon);
                  card.append(anchor);
                  card.append(cardBody);
                  cardCol.append(card);

                  pageWrapper.append(cardCol);
                });
              },
              error: function (xhr, status, error) {
                console.log(error);
              },
            });
          } else {
            mostrarModalDeAdvertencia("Seleccione al menos una categoría");
          }
        }

        if (contenedor === "tags") {
          if (selectedTags.length > 0) {
            $.ajax({
              url: "procesarInformacion/filters/filters.php",
              type: "POST",
              data: {
                categoriesData: selectedCategories,
                tagsData: selectedTags,
                action: "filterPostsByTags",
              },
              success: function (response) {
                var pageWrapper = $(".row-cards");

                var data = JSON.parse(response);

                pageWrapper.empty();

                data.forEach(function (post) {
                  var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                  var card = $(
                    '<div class="card card-sm position-relative"></div>'
                  );

                  var deleteIcon = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon delete-icon position-absolute top-0 end-0 m-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="top: -10px;"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'
                  );

                  var editIcon = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon edit-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line></svg>'
                  );
                  editIcon.css("cursor", "pointer");

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

                  editIcon.attr("data-id", post.id_post);
                  deleteIcon.attr("data-id", post.id_post);

                  cardBody.append(title);
                  cardBody.append(editIcon);
                  card.append(deleteIcon);
                  card.append(anchor);
                  card.append(cardBody);
                  cardCol.append(card);

                  pageWrapper.append(cardCol);
                });
              },
              error: function (xhr, status, error) {
                console.log(error);
              },
            });
          } else {
            mostrarModalDeAdvertencia(
              "Seleccione al menos una categoria o etiqueta"
            );
          }
        }
      });
  }
});

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
      // Previous = changeCategory
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

function getTags(idCategorias, tipoContenedor, tipoAccion) {
  $.ajax({
    url: "procesarInformacion/filters/filters.php",
    type: "POST",
    data: {
      action: tipoAccion,
      categoriesData: idCategorias,
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
