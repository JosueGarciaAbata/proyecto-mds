/**
 *
 *
 *Cargar  los post del usuario
 *
 *
 */

var tipoConsulta = null;

$(document).ready(function () {
  var pageWrapper = $(".row-cards");
  var postId = -1;
  var projectId = -1;

  function getPosts() {
    $.ajax({
      // Ruta para traer informacion de usuarios  de post no registrados.
      url: "../procesarInformacion/comments_no_register/comments_posts.php",
      type: "POST",
      data: {
        action: "getPostsVisitor",
      },
      success: function (response) {
        var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto

        // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
        pageWrapper.empty();

        //Generar los contenedores con la informacion del post
        data.forEach(function (post) {
          $("#sendCommentBtn").attr("data-tipo", "post");

          var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
          var card = $('<div class="card card-sm position-relative"></div>');

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

          var authorName = $("<span>" + post.nombre_usuario + "</span>");

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
              url: "../procesarInformacion/comments_no_register/obtener_datos_posts.php", // Cambia "obtener_datos_post.php" por la ruta real de tu script para obtener datos del post
              method: "POST",
              data: {
                action: "get_data_post",
                postId: postId,
              },
              // Entra a llenar el modal
              success: function (response) {
                console.log("Entrado a llenar el modal...");
                var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                $(".h1-title-post").text(
                  data[0].nombre_usuario +
                    "'s" +
                    " Post" +
                    " - " +
                    data[0].titulo_post
                );

                if (data.some((item) => item.contenido_comentario !== null)) {
                  data.forEach(function (comment) {
                    // Crear el contenedor del comentario
                    var commentContainer = $(
                      '<div class="comment-container"></div>'
                    ).css({
                      display: "flex",
                      alignItems: "center",
                      marginBottom: "10px", // Espacio entre cada comentario
                    });

                    // Agregar la imagen del autor del comentario
                    var authorImageUrl = comment.ubicacion_foto_perfil_usuario
                      ? comment.ubicacion_foto_perfil_usuario
                      : "../img/defaulAvatar.png";
                    var authorImage = $(
                      '<img src="' +
                        authorImageUrl +
                        '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                    );

                    var valitedAuthorName = comment.id_usuario_comentario
                      ? comment.nombre_usuario
                      : "Anonimo";

                    // Agregar el nombre del autor del comentario
                    var authorName = $("<p>" + valitedAuthorName + ": </p>");

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
                console.error("Error al obtener los datos del post:", error);
              },
            });

            // Mostrar el modal
            $("#commentsModal").modal("show");
          });
        });
      },
      error: function (xhr, status, error) {
        mostrarModalDeAdvertencia("An error has occurred");
      },
    });
  }

  // Obtener los proyectos
  function getProjects() {
    $.ajax({
      // Ruta para traer informacion de usuarios  de post no registrados.
      url: "../procesarInformacion/comments_no_register/comments_projects.php",
      type: "POST",
      data: {
        action: "getProjectsVisitor",
      },
      success: function (response) {
        var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto

        //Generar los contenedores con la informacion del post
        data.forEach(function (proyecto) {
          var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
          var card = $('<div class="card card-sm position-relative"></div>');

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
          var authorImageUrl = proyecto.ubicacion_foto_perfil_usuario
            ? proyecto.ubicacion_foto_perfil_usuario
            : "../img/defaulAvatar.png";

          // Asignar imagen del autor
          var authorImage = $(
            '<img src="' +
              authorImageUrl +
              '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin: 10px 10px;">'
          );

          var authorName = $("<span>" + proyecto.nombre_usuario + "</span>");

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
            projectId = $(this).data("id");
            console.log("id proyecto: " + projectId);

            $("#commentsContainerProjects").empty();
            $("#sendCommentBtnProjects").attr("data-id", projectId);

            // Realizar una solicitud AJAX para obtener los datos del post
            $.ajax({
              url: "../procesarInformacion/comments_no_register/obtener_datos_projects.php", // Cambia "obtener_datos_post.php" por la ruta real de tu script para obtener datos del post
              method: "POST",
              data: {
                action: "get_data_projects",
                proyectoId: projectId,
              },
              // Entra a llenar el modal
              success: function (response) {
                console.log("Entrado a llenar el modal...");
                var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
                $(".h1-title-projects").text(
                  data[0].nombre_usuario +
                    "'s" +
                    " Project" +
                    " - " +
                    data[0].titulo_proyecto
                );

                if (data.some((item) => item.contenido_comentario !== null)) {
                  data.forEach(function (comment) {
                    // Crear el contenedor del comentario
                    var commentContainer = $(
                      '<div class="comment-container-projects"></div>'
                    ).css({
                      display: "flex",
                      alignItems: "center",
                      marginBottom: "10px", // Espacio entre cada comentario
                    });

                    // Agregar la imagen del autor del comentario
                    var authorImageUrl = comment.ubicacion_foto_perfil_usuario
                      ? comment.ubicacion_foto_perfil_usuario
                      : "../img/defaulAvatar.png";
                    var authorImage = $(
                      '<img src="' +
                        authorImageUrl +
                        '" alt="Autor" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">'
                    );

                    var valitedAuthorName = comment.id_usuario_comentario
                      ? comment.nombre_usuario
                      : "Anonimo";

                    // Agregar el nombre del autor del comentario
                    var authorName = $("<p>" + valitedAuthorName + ": </p>");

                    // Crear el contenido del comentario
                    var commentContent = $(
                      "<p>" + comment.contenido_comentario + "</p>"
                    );

                    // Adjuntar la imagen, el nombre del autor y el contenido del comentario al contenedor del comentario
                    commentContainer.append(authorImage);
                    commentContainer.append(authorName);
                    commentContainer.append(commentContent);

                    // Adjuntar el contenedor del comentario al formulario de comentarios
                    $("#commentsContainerProjects").append(commentContainer);
                  });

                  $("#noCommentsMessageProjects").hide();
                } else {
                  $("#noCommentsMessageProjects").show();
                }
              },
              error: function (xhr, status, error) {
                console.error("Error al obtener los datos del post:", error);
              },
            });

            // Mostrar el modal
            $("#commentsModalProjects").modal("show");
          });
        });
      },
      error: function (xhr, status, error) {
        mostrarModalDeAdvertencia("An error has occurred");
      },
    });
  }

  //Obtener los posts
  getPosts();

  // Mostrar proyectos
  getProjects();

  // Boton de enviar post
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
          url: "../procesarInformacion/comments_no_register/validar_comentarios_posts.php",
          type: "POST",
          data: {
            action: "insertComment",
            postId: postId,
            commentContent: commentContent,
          },
          success: function (response) {
            console.log(response);
            if (response == "true") {
              mostrarModalExito("Comentario enviado a revision");
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
        mostrarModalDeAdvertencia("No puede ingresar palabras vacias.");
      }
    });

  // Boton de enviar proyecto
  $("#sendCommentBtnProjects")
    .off()
    .on("click", function () {
      var commentContent = $("#commentTextProjects").val().trim();

      // Verificar si el comentario no está vacío antes de enviarlo
      if (commentContent !== "" && projectId != -1) {
        // Aquí puedes enviar el comentario al servidor utilizando AJAX o cualquier otro método
        console.log("Enviando comentario:", commentContent);
        console.log("ID del post:", projectId);
        $.ajax({
          url: "../procesarInformacion/comments_no_register/validar_comentarios_projects.php",
          type: "POST",
          data: {
            action: "insertComment",
            projectId: projectId,
            commentContent: commentContent,
          },
          success: function (response) {
            console.log(response);
            if (response == "true") {
              mostrarModalExito("Comentario enviado a revision");
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
        mostrarModalDeAdvertencia("No puede ingresar palabras vacias.");
      }
    });
});
