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
 *Cargar  los post del usuario
 *
 *
 */

$(document).ready(function () {
  var pageWrapper = $(".row-cards");

  function getPosts() {
    $.ajax({
      url: "procesarInformacion/posts/posts.php",
      type: "POST",
      data: {
        action: "selectPosts",
      },
      success: function (response) {
        var data = JSON.parse(response); // Convertir la respuesta JSON en un objeto
        console.log(data);

        // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
        pageWrapper.empty();

        //Generar los contenedores con la informacion del post
        data.forEach(function (post) {
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

          //Cargar imagen del post o generica

          var imageUrl =
            post.ubicacion_imagen_post &&
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

          // Asignar el id del   post al ícono de editar y al ícono de borrar
          editIcon.attr("data-id", post.id_post);
          deleteIcon.attr("data-id", post.id_post);

          // Adjuntar elementos al DOM para formar la estructura del post
          cardBody.append(title);
          cardBody.append(editIcon); // Adjuntar el SVG de editar al extremo derecho del cardBody
          card.append(deleteIcon); // Adjuntar el SVG de borrado a la tarjeta
          card.append(anchor);
          card.append(cardBody);
          cardCol.append(card);

          // Agregar la tarjeta al contenedor principal (pageWrapper)
          pageWrapper.append(cardCol);

          //Generar funcionalidad al boton de borrado del post
          deleteIcon.on("click", function () {
            $("#deleteModal").modal("show");

            // Configurar el botón de confirmar borrado
            $("#confirmDeleteBtn")
              .off("click")
              .on("click", function () {
                $.ajax({
                  url: "procesarInformacion/posts/posts.php",
                  type: "POST",
                  data: {
                    id_post: post.id_post,
                    action: "deletePost",
                  },
                  success: function (response) {
                    if (response == "true") {
                      mostrarModalExito("Deleted Post");
                      setTimeout(function () {
                        window.location.href = "index.php";
                      }, 2500);
                    } else {
                      mostrarModalDeAdvertencia("Could not delete the post");
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
            $.ajax({
              url: "procesarInformacion/posts/posts.php",
              type: "POST",
              data: {
                action: "getInfoUpdatePost",
                id_post: post.id_post,
              },
              success: function (response) {
                var data = JSON.parse(response);

                // Acceder a la información del post
                var postInfo = data["post_info"];

                // Acceder a las etiquetas asociadas al post
                var labels = data["etiquetas"];

                // Rellenar los campos con la información del post
                $("#title_post").val(postInfo["titulo_post"]);
                $("#content_post").val(postInfo["contenido_textual_post"]);
                $("#state").val(postInfo["id_estado_post"]);

                // Actualizar la imagen del post
                if (postInfo["ubicacion_imagen_post"] !== "") {
                  $("#postImage").attr(
                    "src",
                    postInfo["ubicacion_imagen_post"]
                  );
                } else {
                  $("#postImage").attr("src", "../img/genericUploadImage.jpg");
                }

                // Rellenar la categoría del post
                $("#categoria").val(postInfo["id_categoria_post"]);

                // Modificar el título superior
                $("#superiorTitle").text("Editar Post");

                $("#imageModal").modal("show");
                // Recuperar las etiquetas asociadas al post
                var etiquetas = data["etiquetas"];

                // Recuperar las etiquetas de la categoría del post

                //Esta seccion del codigo permite que se muestren activas las etiquetas asociadas al post
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

                    // Verificar si esta etiqueta está asociada al post actual y marcar el checkbox correspondiente
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

                $("#btn_post").attr("data_info", "update");
                $("#btn_post").attr("data_id_post", post.id_post);
              },
              error: function (xhr, status, error) {
                mostrarModalDeAdvertencia("An error has occurred");
              },
            });
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
});

/*
 *
 *
 *Validar informacion ingresada
 *
 *
 */

$(document).ready(function () {
  $("#postForm").validate({
    rules: {
      title_post: {
        required: true,
        minlength: 10,
        maxlength: 50,
      },
      content_post: {
        required: true,
        minlength: 10,
        maxlength: 350,
      },
    },
    messages: {
      title_post: {
        required: "Please enter a name",
        minlength: "Please enter at least 10 characters",
        maxlength: "The maximum number of characters for the title is 50",
      },
      content_post: {
        required: "Please enter content for the post",
        minlength: "Please enter at least 10 characters",
        maxlength: "The maximum number of characters for the content is 350",
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
      url: "procesarInformacion/posts/posts.php",
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

$("#postInput").change(function () {
  $("#postFormImage").submit();
});

/*
 *
 *
 * Cambiar dinamicamente la imagen del post
 *
 */
$("#postFormImage").submit(function (event) {
  event.preventDefault();
  var formData = new FormData(this);
  formData.append("action", "imgPostTemporal");

  $.ajax({
    url: "procesarInformacion/posts/posts.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);

      if (response == "false") {
        mostrarModalDeAdvertencia("Error al cargar la imagen");
      } else {
        var imageUrl = response;
        $("#postImage").attr("src", imageUrl);
      }
    },
    error: function (xhr, status, error) {
      console.log(error);
      mostrarModalDeAdvertencia("An error has occurred");
    },
  });
});

$("#btn_create").click(function () {
  //Esta seccion le permite saber al modal que se usara para crear un post
  $("#btn_post").attr("data_info", "create");
  $("#superiorTitle").text("Crear Post");
});

/*
 *
 *
 *Procesar informacion del post
 *
 *
 */

$("#btn_post").click(function () {
  //Recuperar accion a realizar creacion  o actualiacion
  var dataInfoValue = $(this).attr("data_info");

  //Recuperar imagen
  var imageFile = $("#postInput")[0].files[0];
  if (
    $("#postForm").valid() &&
    $("#categoria").val() !== null &&
    $("#state").val() != null
  ) {
    //Guardar etiquetas seleccionadas
    var labelsActive = [];
    $(".form-selectgroup input[type='checkbox']:checked").each(function () {
      var labelId = $(this).data("id");
      labelsActive.push(labelId);
    });

    //Guardar informacion en formData
    var formData = new FormData();
    if (imageFile) {
      formData.append("postImage", imageFile);
    }

    if (dataInfoValue == "create") {
      formData.append("action", "createNewPost");
    } else {
      formData.append("action", "updatePost");
      formData.append("id_post", $(this).attr("data_id_post"));
    }

    formData.append("title", $("#title_post").val());
    formData.append("content", $("#content_post").val());
    formData.append("id_category", $("#categoria").val());
    formData.append("state", $("#state").val());
    formData.append("labelsActive", JSON.stringify(labelsActive));
    //Enviar informacion del post al backend
    $.ajax({
      url: "procesarInformacion/posts/posts.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        if (response == "true") {
          mostrarModalExito("Post Saved with Success");
          setTimeout(function () {
            window.location.href = "index.php";
          }, 2500);
        } else {
          mostrarModalDeAdvertencia("Could not save the post");
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
        mostrarModalDeAdvertencia("Connection error");
      },
    });
  } else {
    mostrarModalDeAdvertencia("Necessary missing information");
  }
});
