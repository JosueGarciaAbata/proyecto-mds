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
  });

  // Enviar los comentarios del post
  $("#sendCommentBtn")
    .off()
    .on("click", function () {
      var commentContent = $("#commentText").val().trim();

      // Verificar si el comentario no está vacío antes de enviarlo
      if (commentContent !== "" && postId != -1) {
        // Aquí puedes enviar el comentario al servidor utilizando AJAX o cualquier otro método
        console.log("Enviando comentario:", commentContent);
        console.log("ID del post:", postId);

        $.ajax({
          url: "procesarInformacion/comments_register/validar_comentarios_posts.php",
          type: "POST",
          data: {
            action: "insertComment",
            postId: postId,
            commentContent: commentContent,
          },
          success: function (response) {
            console.log(response);

            if (response === "true") {
              mostrarModalExito("Comentario enviado a revisión");
            } else if (response === "trueRegister") {
              mostrarModalExito("Comentario añadido exitosamente");
            } else {
              mostrarModalDeAdvertencia("No se ha podido enviar el comentario");
            }
          },
          error: function (xhr, status, error) {
            console.error("Error al enviar el comentario:", error);
          },
        });
      } else {
        // Si el comentario está vacío, puedes mostrar un mensaje de advertencia al usuario o simplemente ignorar el envío
        mostrarModalDeAdvertencia("No puede ingresar palabras vacías.");
      }
    });

  // Enviar los comentarios de los proyectos
  // Boton de enviar proyecto
  $("#sendCommentBtnProjects")
    .off()
    .on("click", function () {
      var commentContent = $("#commentTextProjects").val().trim();

      // Verificar si el comentario no está vacío antes de enviarlo
      if (commentContent !== "" && proyectoId != -1) {
        // Aquí puedes enviar el comentario al servidor utilizando AJAX o cualquier otro método
        console.log("Enviando comentario:", commentContent);
        console.log("ID del post:", proyectoId);

        $.ajax({
          url: "procesarInformacion/comments_register/validar_comentarios_projects.php",
          type: "POST",
          data: {
            action: "insertComment",
            projectId: proyectoId,
            commentContent: commentContent,
          },
          success: function (response) {
            console.log(response);
            if (response === "true") {
              mostrarModalExito("Comentario enviado a revisión");
            } else if (response === "trueRegister") {
              mostrarModalExito("Comentario añadido exitosamente");
            } else {
              mostrarModalDeAdvertencia("No se ha podido enviar el comentario");
            }
          },
          error: function (xhr, status, error) {
            console.error("Error al enviar el comentario:", error);
          },
        });
      } else {
        // Si el comentario está vacío, puedes mostrar un mensaje de advertencia al usuario o simplemente ignorar el envío
        mostrarModalDeAdvertencia("No puede ingresar palabras vacías.");
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
              url: "procesarInformacion/filters/comments_filters.php",
              type: "POST",
              data: {
                categoriesData: selectedCategories,
                action: "filterPostsByCategoriesRegister",
              },
              success: function (response) {
                var pageWrapper = $(".row-cards");
                var data = JSON.parse(response);
                console.log(data);

                pageWrapper.empty();

                //Generar los contenedores con la informacion del post
                data.forEach(function (post) {
                  $("#sendCommentBtn").attr("data-tipo", "post");

                  var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                  var card = $(
                    '<div class="card card-sm position-relative"></div>'
                  );

                  // SVG de editar al extremo derecho del cardBody
                  var commentIcon = $(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">' +
                      '<path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>' +
                      '<path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>' +
                      "</svg>"
                  );
                  commentIcon.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

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

                  // Verificar si la imagen del autor es nula y establecer una imagen predeterminada en ese caso
                  var authorImageUrl = post.ubicacion_foto_perfil_usuario
                    ? post.ubicacion_foto_perfil_usuario
                    : "../img/defaulAvatar.png";

                  // Asignar imagen del autor
                  var authorImage = $(
                    '<img src="' +
                      authorImageUrl +
                      '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin: 10px 10px;">'
                  );

                  var authorName = $(
                    "<span>" + post.nombre_usuario + "</span>"
                  );

                  var authorContainer = $(
                    '<div class="d-flex align-items-center"></div>'
                  );
                  authorContainer.append(authorImage);
                  authorContainer.append(authorName);

                  var content = $(
                    '<p style="margin: 10px 10px;">' +
                      post.contenido_textual_post +
                      "</p>"
                  );

                  anchor.append(imageElement);
                  var cardBody = $(
                    '<div class="card-body d-flex align-items-center justify-content-between"></div>'
                  );
                  var title = $(
                    '<h4 class="mb-0" style="margin-left: 10px;">' +
                      post.titulo_post +
                      "</h4>"
                  );

                  // Asignar el id del   post al ícono de editar y al ícono de borrar
                  commentIcon.attr("data-id", post.id_post);

                  // Adjuntar elementos al DOM para formar la estructura del post
                  cardBody.append(title);
                  cardBody.append(commentIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody

                  card.append(authorContainer);
                  card.append(content);
                  card.append(anchor);
                  card.append(cardBody);
                  cardCol.append(card);

                  // Agregar la tarjeta al contenedor principal (pageWrapper)
                  pageWrapper.append(cardCol);

                  //Generar funcionalidad al boton de comentar.
                  // Aqui modificar y dar la implementacion para "mostrar" y generar comentarios.
                  commentIcon.on("click", function () {
                    postId = $(this).data("id");
                    console.log("id post: " + postId);

                    $("#commentsContainer").empty();
                    $("#sendCommentBtn").attr("data-id", postId);

                    // Realizar una solicitud AJAX para obtener los datos del post
                    $.ajax({
                      url: "procesarInformacion/comments_register/obtener_datos_posts.php",
                      method: "POST",
                      data: {
                        action: "get_data_post_register",
                        postId: postId,
                      },
                      // Entra a llenar el modal
                      success: function (response) {
                        console.log("Entrado a llenar el modal...");
                        var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                        console.log(data);
                        $(".h1-title-post").text(
                          data.nombre_usuario +
                            "'s" +
                            " Post" +
                            " - " +
                            data.titulo_post
                        );

                        if (data.comentarios.length > 0) {
                          data.comentarios.forEach(function (comment) {
                            // Crear el contenedor del comentario
                            var commentContainer = $(
                              '<div class="comment-container"></div>'
                            ).css({
                              display: "flex",
                              alignItems: "center",
                              marginBottom: "10px", // Espacio entre cada comentario
                            });

                            // Agregar la imagen del autor del comentario
                            var authorImageUrl =
                              comment.ubicacion_foto_perfil_usuario
                                ? comment.ubicacion_foto_perfil_usuario
                                : "../img/defaulAvatar.png";
                            var authorImage = $(
                              '<img src="' +
                                authorImageUrl +
                                '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                            );

                            var valitedAuthorName =
                              comment.id_usuario_comentario
                                ? comment.nombre_usuario
                                : "Anonimo";

                            // Agregar el nombre del autor del comentario
                            var authorName = $(
                              "<p>" + valitedAuthorName + ": </p>"
                            );

                            // Crear el contenido del comentario
                            var commentContent = $(
                              "<p>" + comment.contenido_comentario + "</p>"
                            );

                            // Adjuntar la imagen, el nombre del autor y el contenido del comentario al contenedor del comentario
                            commentContainer.append(authorImage);
                            commentContainer.append(authorName);
                            commentContainer.append(commentContent);

                            // Adjuntar el contenedor del comentario al formulario de comentarios
                            $("#commentsContainer").append(commentContainer);
                          });
                          $("#noCommentsMessage").hide();
                        } else {
                          $("#noCommentsMessage").show();
                        }
                      },
                      error: function (xhr, status, error) {
                        console.error(
                          "Error al obtener los datos del post:",
                          error
                        );
                      },
                    });

                    // Mostrar el modal
                    $("#commentsModal").modal("show");
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

      console.log(selectedCategory);

      if (selectedCategory.length > 0) {
        var categoryId = $(this).data("id");
        $.ajax({
          url: "procesarInformacion/filters/comments_filters.php",
          type: "POST",
          data: {
            action: "getOneCategoryTags",
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
                url: "procesarInformacion/filters/comments_filters.php",
                type: "POST",
                data: {
                  categoryData: selectedCategory,
                  tagsData: selectedTags,
                  action: "filterPostsByTagsRegister",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  pageWrapper.empty();

                  var data = JSON.parse(response);

                  data.forEach(function (post) {
                    $("#sendCommentBtn").attr("data-tipo", "post");

                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

                    // SVG de editar al extremo derecho del cardBody
                    var commentIcon = $(
                      '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">' +
                        '<path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>' +
                        '<path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>' +
                        "</svg>"
                    );
                    commentIcon.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

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

                    // Verificar si la imagen del autor es nula y establecer una imagen predeterminada en ese caso
                    var authorImageUrl = post.ubicacion_foto_perfil_usuario
                      ? post.ubicacion_foto_perfil_usuario
                      : "../img/defaulAvatar.png";

                    // Asignar imagen del autor
                    var authorImage = $(
                      '<img src="' +
                        authorImageUrl +
                        '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin: 10px 10px;">'
                    );

                    var authorName = $(
                      "<span>" + post.nombre_usuario + "</span>"
                    );

                    var authorContainer = $(
                      '<div class="d-flex align-items-center"></div>'
                    );
                    authorContainer.append(authorImage);
                    authorContainer.append(authorName);

                    var content = $(
                      '<p style="margin: 10px 10px;">' +
                        post.contenido_textual_post +
                        "</p>"
                    );

                    anchor.append(imageElement);
                    var cardBody = $(
                      '<div class="card-body d-flex align-items-center justify-content-between"></div>'
                    );
                    var title = $(
                      '<h4 class="mb-0" style="margin-left: 10px;">' +
                        post.titulo_post +
                        "</h4>"
                    );

                    // Asignar el id del   post al ícono de editar y al ícono de borrar
                    commentIcon.attr("data-id", post.id_post);

                    // Adjuntar elementos al DOM para formar la estructura del post
                    cardBody.append(title);
                    cardBody.append(commentIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody

                    card.append(authorContainer);
                    card.append(content);
                    card.append(anchor);
                    card.append(cardBody);
                    cardCol.append(card);

                    // Agregar la tarjeta al contenedor principal (pageWrapper)
                    pageWrapper.append(cardCol);

                    //Generar funcionalidad al boton de comentar.
                    // Aqui modificar y dar la implementacion para "mostrar" y generar comentarios.
                    commentIcon.on("click", function () {
                      postId = $(this).data("id");
                      console.log("id post: " + postId);

                      $("#commentsContainer").empty();
                      $("#sendCommentBtn").attr("data-id", postId);

                      // Realizar una solicitud AJAX para obtener los datos del post
                      $.ajax({
                        url: "procesarInformacion/comments_register/obtener_datos_posts.php",
                        method: "POST",
                        data: {
                          action: "get_data_post_register",
                          postId: postId,
                        },
                        // Entra a llenar el modal
                        success: function (response) {
                          console.log("Entrado a llenar el modal...");
                          var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                          console.log(data);
                          $(".h1-title-post").text(
                            data.nombre_usuario +
                              "'s" +
                              " Post" +
                              " - " +
                              data.titulo_post
                          );

                          if (data.comentarios.length > 0) {
                            data.comentarios.forEach(function (comment) {
                              // Crear el contenedor del comentario
                              var commentContainer = $(
                                '<div class="comment-container"></div>'
                              ).css({
                                display: "flex",
                                alignItems: "center",
                                marginBottom: "10px", // Espacio entre cada comentario
                              });

                              // Agregar la imagen del autor del comentario
                              var authorImageUrl =
                                comment.ubicacion_foto_perfil_usuario
                                  ? comment.ubicacion_foto_perfil_usuario
                                  : "../img/defaulAvatar.png";
                              var authorImage = $(
                                '<img src="' +
                                  authorImageUrl +
                                  '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                              );

                              var valitedAuthorName =
                                comment.id_usuario_comentario
                                  ? comment.nombre_usuario
                                  : "Anonimo";

                              // Agregar el nombre del autor del comentario
                              var authorName = $(
                                "<p>" + valitedAuthorName + ": </p>"
                              );

                              // Crear el contenido del comentario
                              var commentContent = $(
                                "<p>" + comment.contenido_comentario + "</p>"
                              );

                              // Adjuntar la imagen, el nombre del autor y el contenido del comentario al contenedor del comentario
                              commentContainer.append(authorImage);
                              commentContainer.append(authorName);
                              commentContainer.append(commentContent);

                              // Adjuntar el contenedor del comentario al formulario de comentarios
                              $("#commentsContainer").append(commentContainer);
                            });
                            $("#noCommentsMessage").hide();
                          } else {
                            $("#noCommentsMessage").show();
                          }
                        },
                        error: function (xhr, status, error) {
                          console.error(
                            "Error al obtener los datos del post:",
                            error
                          );
                        },
                      });

                      // Mostrar el modal
                      $("#commentsModal").modal("show");
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
                url: "procesarInformacion/filters/comments_filters.php",
                type: "POST",
                data: {
                  categoriesData: selectedCategories,
                  action: "filterProjectsByCategoriesRegister",
                },
                success: function (response) {
                  console.log(response);
                  var pageWrapper = $(".row-cards");
                  var data = JSON.parse(response);

                  pageWrapper.empty();

                  data.forEach(function (proyecto) {
                    var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                    var card = $(
                      '<div class="card card-sm position-relative"></div>'
                    );

                    // SVG de editar al extremo derecho del cardBody
                    var commentIcon = $(
                      '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">' +
                        '<path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>' +
                        '<path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>' +
                        "</svg>"
                    );
                    commentIcon.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

                    var anchor = $('<a href="#" class="d-block"></a>');

                    //Cargar imagen del post o generica
                    var imageUrl =
                      proyecto.ubicacion_imagen_proyecto != null &&
                      proyecto.ubicacion_imagen_proyecto.trim() !== ""
                        ? proyecto.ubicacion_imagen_proyecto
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

                    // Verificar si la imagen del autor es nula y establecer una imagen predeterminada en ese caso
                    var authorImageUrl = proyecto.ubicacion_foto_perfil_usuario
                      ? proyecto.ubicacion_foto_perfil_usuario
                      : "../img/defaulAvatar.png";

                    // Asignar imagen del autor
                    var authorImage = $(
                      '<img src="' +
                        authorImageUrl +
                        '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin: 10px 10px;">'
                    );

                    var authorName = $(
                      "<span>" + proyecto.nombre_usuario + "</span>"
                    );

                    var authorContainer = $(
                      '<div class="d-flex align-items-center"></div>'
                    );
                    authorContainer.append(authorImage);
                    authorContainer.append(authorName);

                    var content = $(
                      '<p style="margin: 10px 10px;">' +
                        proyecto.descripcion_proyecto +
                        "</p>"
                    );

                    anchor.append(imageElement);
                    var cardBody = $(
                      '<div class="card-body d-flex align-items-center justify-content-between"></div>'
                    );
                    var title = $(
                      '<h4 class="mb-0" style="margin-left: 10px;">' +
                        proyecto.titulo_proyecto +
                        "</h4>"
                    );

                    // Asignar el id del   post al ícono de editar y al ícono de borrar
                    commentIcon.attr("data-id", proyecto.id_proyecto);

                    // Adjuntar elementos al DOM para formar la estructura del post
                    cardBody.append(title);
                    cardBody.append(commentIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody

                    card.append(authorContainer);
                    card.append(content);
                    card.append(anchor);
                    card.append(cardBody);
                    cardCol.append(card);

                    // Agregar la tarjeta al contenedor principal (pageWrapper)
                    pageWrapper.append(cardCol);

                    //Generar funcionalidad al boton de comentar.
                    // Aqui modificar y dar la implementacion para "mostrar" y generar comentarios.
                    commentIcon.on("click", function () {
                      proyectoId = $(this).data("id");
                      console.log("id proyecto: " + proyectoId);

                      $("#commentsContainerProjects").empty();
                      $("#sendCommentBtnProjects").attr("data-id", proyectoId);

                      // Realizar una solicitud AJAX para obtener los datos del post
                      $.ajax({
                        url: "procesarInformacion/comments_register/obtener_datos_projects.php", // Cambia "obtener_datos_post.php" por la ruta real de tu script para obtener datos del post
                        method: "POST",
                        data: {
                          action: "get_data_projects_register",
                          proyectoId: proyectoId,
                        },
                        // Entra a llenar el modal
                        success: function (response) {
                          console.log("Entrado a llenar el modal...");
                          var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                          console.log(data);
                          $(".h1-title-projects").text(
                            data.nombre_usuario +
                              "'s" +
                              " Project" +
                              " - " +
                              data.titulo_proyecto
                          );

                          if (data.comentarios.length > 0) {
                            data.comentarios.forEach(function (comment) {
                              // Crear el contenedor del comentario
                              var commentContainer = $(
                                '<div class="comment-container-projects"></div>'
                              ).css({
                                display: "flex",
                                alignItems: "center",
                                marginBottom: "10px", // Espacio entre cada comentario
                              });

                              // Agregar la imagen del autor del comentario
                              var authorImageUrl =
                                comment.ubicacion_foto_perfil_usuario
                                  ? comment.ubicacion_foto_perfil_usuario
                                  : "../img/defaulAvatar.png";
                              var authorImage = $(
                                '<img src="' +
                                  authorImageUrl +
                                  '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                              );

                              var valitedAuthorName =
                                comment.id_usuario_comentario
                                  ? comment.nombre_usuario
                                  : "Anonimo";

                              // Agregar el nombre del autor del comentario
                              var authorName = $(
                                "<p>" + valitedAuthorName + ": </p>"
                              );

                              // Crear el contenido del comentario
                              var commentContent = $(
                                "<p>" + comment.contenido_comentario + "</p>"
                              );

                              // Adjuntar la imagen, el nombre del autor y el contenido del comentario al contenedor del comentario
                              commentContainer.append(authorImage);
                              commentContainer.append(authorName);
                              commentContainer.append(commentContent);

                              // Adjuntar el contenedor del comentario al formulario de comentarios
                              $("#commentsContainerProjects").append(
                                commentContainer
                              );
                            });
                            $("#noCommentsMessageProjects").hide();
                          } else {
                            $("#noCommentsMessageProjects").show();
                          }
                        },
                        error: function (xhr, status, error) {
                          console.error(
                            "Error al obtener los datos del post:",
                            error
                          );
                        },
                      });

                      // Mostrar el modal
                      $("#commentsModalProjects").modal("show");
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
            url: "procesarInformacion/filters/comments_filters.php",
            type: "POST",
            data: {
              action: "getOneCategoryTags",
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
                  url: "procesarInformacion/filters/comments_filters.php",
                  type: "POST",
                  data: {
                    categoryData: selectedCategory,
                    tagsData: selectedTags,
                    action: "filterProjectsByTagsRegister",
                  },
                  success: function (response) {
                    console.log(response);
                    var pageWrapper = $(".row-cards");
                    pageWrapper.empty();

                    var data = JSON.parse(response);

                    data.forEach(function (proyecto) {
                      var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
                      var card = $(
                        '<div class="card card-sm position-relative"></div>'
                      );

                      // SVG de editar al extremo derecho del cardBody
                      var commentIcon = $(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">' +
                          '<path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>' +
                          '<path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>' +
                          "</svg>"
                      );
                      commentIcon.css("cursor", "pointer"); // Establecer un cursor de puntero en el ícono para indicar que es interactivo

                      var anchor = $('<a href="#" class="d-block"></a>');

                      //Cargar imagen del post o generica
                      var imageUrl =
                        proyecto.ubicacion_imagen_proyecto != null &&
                        proyecto.ubicacion_imagen_proyecto.trim() !== ""
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

                      // Verificar si la imagen del autor es nula y establecer una imagen predeterminada en ese caso
                      var authorImageUrl =
                        proyecto.ubicacion_foto_perfil_usuario
                          ? proyecto.ubicacion_foto_perfil_usuario
                          : "../img/defaulAvatar.png";

                      // Asignar imagen del autor
                      var authorImage = $(
                        '<img src="' +
                          authorImageUrl +
                          '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin: 10px 10px;">'
                      );

                      var authorName = $(
                        "<span>" + proyecto.nombre_usuario + "</span>"
                      );

                      var authorContainer = $(
                        '<div class="d-flex align-items-center"></div>'
                      );
                      authorContainer.append(authorImage);
                      authorContainer.append(authorName);

                      var content = $(
                        '<p style="margin: 10px 10px;">' +
                          proyecto.descripcion_proyecto +
                          "</p>"
                      );

                      anchor.append(imageElement);
                      var cardBody = $(
                        '<div class="card-body d-flex align-items-center justify-content-between"></div>'
                      );
                      var title = $(
                        '<h4 class="mb-0" style="margin-left: 10px;">' +
                          proyecto.titulo_proyecto +
                          "</h4>"
                      );

                      // Asignar el id del   post al ícono de editar y al ícono de borrar
                      commentIcon.attr("data-id", proyecto.id_proyecto);

                      // Adjuntar elementos al DOM para formar la estructura del post
                      cardBody.append(title);
                      cardBody.append(commentIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody

                      card.append(authorContainer);
                      card.append(content);
                      card.append(anchor);
                      card.append(cardBody);
                      cardCol.append(card);

                      // Agregar la tarjeta al contenedor principal (pageWrapper)
                      pageWrapper.append(cardCol);

                      //Generar funcionalidad al boton de comentar.
                      // Aqui modificar y dar la implementacion para "mostrar" y generar comentarios.
                      commentIcon.on("click", function () {
                        proyectoId = $(this).data("id");
                        console.log("id proyecto: " + proyectoId);

                        $("#commentsContainerProjects").empty();
                        $("#sendCommentBtnProjects").attr(
                          "data-id",
                          proyectoId
                        );

                        // Realizar una solicitud AJAX para obtener los datos del post
                        $.ajax({
                          url: "procesarInformacion/comments_register/obtener_datos_projects.php", // Cambia "obtener_datos_post.php" por la ruta real de tu script para obtener datos del post
                          method: "POST",
                          data: {
                            action: "get_data_projects_register",
                            proyectoId: proyectoId,
                          },
                          // Entra a llenar el modal
                          success: function (response) {
                            console.log("Entrado a llenar el modal...");
                            var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                            console.log(data);
                            $(".h1-title-projects").text(
                              data.nombre_usuario +
                                "'s" +
                                " Project" +
                                " - " +
                                data.titulo_proyecto
                            );

                            if (data.comentarios.length > 0) {
                              data.comentarios.forEach(function (comment) {
                                // Crear el contenedor del comentario
                                var commentContainer = $(
                                  '<div class="comment-container-projects"></div>'
                                ).css({
                                  display: "flex",
                                  alignItems: "center",
                                  marginBottom: "10px", // Espacio entre cada comentario
                                });

                                // Agregar la imagen del autor del comentario
                                var authorImageUrl =
                                  comment.ubicacion_foto_perfil_usuario
                                    ? comment.ubicacion_foto_perfil_usuario
                                    : "../img/defaulAvatar.png";
                                var authorImage = $(
                                  '<img src="' +
                                    authorImageUrl +
                                    '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                                );

                                var valitedAuthorName =
                                  comment.id_usuario_comentario
                                    ? comment.nombre_usuario
                                    : "Anonimo";

                                // Agregar el nombre del autor del comentario
                                var authorName = $(
                                  "<p>" + valitedAuthorName + ": </p>"
                                );

                                // Crear el contenido del comentario
                                var commentContent = $(
                                  "<p>" + comment.contenido_comentario + "</p>"
                                );

                                // Adjuntar la imagen, el nombre del autor y el contenido del comentario al contenedor del comentario
                                commentContainer.append(authorImage);
                                commentContainer.append(authorName);
                                commentContainer.append(commentContent);

                                // Adjuntar el contenedor del comentario al formulario de comentarios
                                $("#commentsContainerProjects").append(
                                  commentContainer
                                );
                              });
                              $("#noCommentsMessageProjects").hide();
                            } else {
                              $("#noCommentsMessageProjects").show();
                            }
                          },
                          error: function (xhr, status, error) {
                            console.error(
                              "Error al obtener los datos del post:",
                              error
                            );
                          },
                        });

                        // Mostrar el modal
                        $("#commentsModalProjects").modal("show");
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

function obtenerSeleccionados(contenedor) {
  return $("#" + contenedor + " input[type='checkbox']:checked")
    .map(function () {
      return $(this).data("id");
    })
    .get();
}

function getCategories(tipoContenedor, tipoAccion) {
  $.ajax({
    url: "procesarInformacion/filters/comments_filters.php",
    type: "POST",
    data: {
      action: tipoAccion,
    },
    success: function (response) {
      console.log(response);
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
    url: "procesarInformacion/filters/comments_filters.php",
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
