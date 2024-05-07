/*
Cambia la forma de seleccionar los filtros segun el tipo: post, proyecto o portafolio.
En el primero se hace por una categoria y n etiquetas.
En el segundo se hace por el proyecto y n categorias.
En el tercero se hace por portafolio y n habilidades.
*/

$(document).ready(function () {
  $("#tipo-filtro").change(function () {
    var tipoSeleccionado = $(this).val();
    var categoriasDiv = $("#categorias-div");
    var etiquetasDiv = $("#etiquetas-div");
    var habilidadesDiv = $("#habilidades-div");

    if (tipoSeleccionado === "post") {
      categoriasDiv.show();
      etiquetasDiv.show();
      habilidadesDiv.hide();

      $("#categorias-filtro").change(function () {
        var categoriaSeleccionada = $(this).val();
        console.log(categoriaSeleccionada);

        if (categoriaSeleccionada) {
          $.ajax({
            url: "procesarInformacion/searches/searches.php",
            type: "POST",
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
              console.log(error);
            },
          });
        }
      });
    } else if (tipoSeleccionado === "proyecto") {
      categoriasDiv.show();
      etiquetasDiv.hide();
      habilidadesDiv.hide();
    } else if (tipoSeleccionado === "portafolio") {
      categoriasDiv.hide();
      etiquetasDiv.hide();
      habilidadesDiv.show();

      $.ajax({
        url: "procesarInformacion/searches/searches.php",
        type: "POST",
        data: {
          action: "getHabilities",
        },
        success: function (response) {
          var labels = JSON.parse(response);

          var formSelectgroup = document.querySelector(".habilities");

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
  });
});

/*
Permite cargar los datos en funcion del filtrado de:
Categoria y n etiquetas.
*/

$(document).ready(function () {
  $("#btn_filter").click(function () {
    var pageWrapper = $(".row-cards");
    var typeSelected = $("#tipo-filtro").val();

    if (typeSelected == "post") {
      // Si se desea hacer en base a n categorias se debe modificar en esta parte. Es decir
      // Se debe recuperar como las etiquetas.
      var categorySelected = $("#categorias-filtro").val();

      var tagsSelected = []; // Limpiar tagsSelected antes de cada click del botón
      $('#etiquetas-div input[type="checkbox"]:checked').each(function () {
        tagsSelected.push($(this).data("id"));
      });

      // Mientras todo este definido
      if (typeSelected && categorySelected && tagsSelected) {
        console.log("Tipo seleccionado: " + typeSelected);
        console.log("Categoria seleccionada: " + categorySelected);
        console.log("Tags seleccionados: " + tagsSelected);
        console.log("Todo definido");

        $.ajax({
          url: "procesarInformacion/searches/searches.php",
          type: "POST",
          data: {
            action: "filterPosts",
            type: typeSelected,
            category: categorySelected,
            tags: tagsSelected,
          },
          success: function (response) {
            pageWrapper.empty();

            var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto

            // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
            pageWrapper.empty();

            // Generar los contenedores con la informacion del post
            data.forEach(function (post) {
              var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
              var card = $(
                '<div class="card card-sm position-relative"></div>'
              );
              var anchor = $('<a href="#" class="d-block"></a>');

              // Cargar imagen del post o generica
              var imageUrl =
                post.ubicacion_imagen_post &&
                post.ubicacion_imagen_post.trim() !== ""
                  ? post.ubicacion_imagen_post
                  : "../img/genericImagePost.jpg";

              // Asignar imagen
              var imageElement = $("<img>");
              imageElement.attr("src", imageUrl);
              imageElement.addClass("card-img-top");

              // Establecer propiedades de la imagen
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

              // Adjuntar elementos al DOM para formar la estructura del post
              cardBody.append(title);
              card.append(anchor);
              card.append(cardBody);
              cardCol.append(card);

              // Agregar la tarjeta al contenedor principal (pageWrapper)
              pageWrapper.append(cardCol);
            });
          },
          error: function (xhr, status, error) {
            console.log(error);
          },
        });
      }
    }
  });
});

/*
Permite obtener los proyectos en base a una categoria.
*/
$(document).ready(function () {
  $("#btn_filter").click(function () {
    var pageWrapper = $(".row-cards");
    var typeSelected = $("#tipo-filtro").val();

    if (typeSelected === "proyecto") {
      var categorySelected = $("#categorias-filtro").val();

      $.ajax({
        url: "procesarInformacion/searches/searches.php",
        type: "POST",
        data: {
          action: "filterProjects",
          category: categorySelected,
        },
        success: function (response) {
          pageWrapper.empty();

          var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto

          // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
          pageWrapper.empty();

          // Generar los contenedores con la informacion del post
          data.forEach(function (project) {
            var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
            var card = $('<div class="card card-sm position-relative"></div>');
            var anchor = $('<a href="#" class="d-block"></a>');

            // Cargar imagen del post o generica
            var imageUrl =
              project.ubicacion_imagen_proyecto &&
              project.ubicacion_imagen_proyecto.trim() !== ""
                ? project.ubicacion_imagen_proyecto
                : "../img/genericImagePost.jpg";

            // Asignar imagen
            var imageElement = $("<img>");
            imageElement.attr("src", imageUrl);
            imageElement.addClass("card-img-top");

            // Establecer propiedades de la imagen
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

            // Adjuntar elementos al DOM para formar la estructura del post
            cardBody.append(title);
            card.append(anchor);
            card.append(cardBody);
            cardCol.append(card);

            // Agregar la tarjeta al contenedor principal (pageWrapper)
            pageWrapper.append(cardCol);
          });
        },
        error: function (xhr, status, error) {
          console.log(error);
        },
      });
    }
  });
});

/*
Permite obtener los portafolios en base a habilidades
*/
$(document).ready(function () {
  $("#btn_filter").click(function () {
    var pageWrapper = $(".row-cards");
    var typeSelected = $("#tipo-filtro").val();

    if (typeSelected == "portafolio") {
      var habilitiesSelected = [];
      $('#habilidades-div input[type="checkbox"]:checked').each(function () {
        habilitiesSelected.push($(this).data("id"));
      });
      // Creo que si esta bien, ya que las habilidades seran obtenidas dinamicamnete
      // de la bd y estas habilidades seran creadas al momento de crear un portafolio.
      // Eso significa, que si no hay portafolios creados, tampoco se puede filtrar.
      $.ajax({
        url: "procesarInformacion/searches/searches.php",
        type: "POST",
        data: {
          action: "filterPortfolio",
          habilities: habilitiesSelected,
        },
        success: function (response) {
          pageWrapper.empty();

          var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
          console.log(data);

          // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
          pageWrapper.empty();

          // Generar los contenedores con la informacion del post
          data.forEach(function (portfolio) {
            var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
            var card = $('<div class="card card-sm position-relative"></div>');
            var anchor = $('<a href="#" class="d-block"></a>');

            // Cargar imagen del post o generica
            var imageUrl =
              portfolio.ubicacion_foto_portafolio &&
              portfolio.ubicacion_foto_portafolio.trim() !== ""
                ? portfolio.ubicacion_foto_portafolio
                : "../img/genericImagePost.jpg";

            // Asignar imagen
            var imageElement = $("<img>");
            imageElement.attr("src", imageUrl);
            imageElement.addClass("card-img-top");

            // Establecer propiedades de la imagen
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
                project.nombre_apellido_portfolio +
                "</h4>"
            );

            // Adjuntar elementos al DOM para formar la estructura del post
            cardBody.append(title);
            card.append(anchor);
            card.append(cardBody);
            cardCol.append(card);

            // Agregar la tarjeta al contenedor principal (pageWrapper)
            pageWrapper.append(cardCol);
          });
        },
        error: function (xhr, status, error) {
          console.log(error);
        },
      });
    }
  });
});
