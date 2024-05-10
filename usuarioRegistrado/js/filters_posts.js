/*
Filtro en función de los post. Usuario registrado
*/

$(document).ready(function () {
  $("#tipo-filtro-post").change(function () {
    var typeSelected = $("#tipo-filtro-post").val();
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
                  action: "filterPostsByCategories",
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
                                mostrarModalDeAdvertencia(
                                  "Could not delete the post"
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
                          $("#content_post").val(
                            postInfo["contenido_textual_post"]
                          );
                          $("#state").val(postInfo["id_estado_post"]);

                          // Actualizar la imagen del post
                          if (postInfo["ubicacion_imagen_post"] !== null) {
                            $("#postImage").attr(
                              "src",
                              postInfo["ubicacion_imagen_post"]
                            );
                          } else {
                            $("#postImage").attr(
                              "src",
                              "../img/genericUploadImage.jpg"
                            );
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

                              // Verificar si esta etiqueta está asociada al post actual y marcar el checkbox correspondiente
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

            if (selectedCategory.length > 0) {
              if (selectedTags.length > 0) {
                $.ajax({
                  url: "procesarInformacion/filters/filters.php",
                  type: "POST",
                  data: {
                    categoryData: selectedCategory,
                    tagsData: selectedTags,
                    action: "filterPostsByTags",
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
                                  mostrarModalDeAdvertencia(
                                    "Could not delete the post"
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
                            $("#content_post").val(
                              postInfo["contenido_textual_post"]
                            );
                            $("#state").val(postInfo["id_estado_post"]);

                            // Actualizar la imagen del post
                            if (postInfo["ubicacion_imagen_post"] !== null) {
                              $("#postImage").attr(
                                "src",
                                postInfo["ubicacion_imagen_post"]
                              );
                            } else {
                              $("#postImage").attr(
                                "src",
                                "../img/genericUploadImage.jpg"
                              );
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
                            var etiquetasCategoria =
                              data["etiquetas_categoria"];
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

                                var spanElement =
                                  document.createElement("span");
                                spanElement.classList.add(
                                  "form-selectgroup-label"
                                );
                                spanElement.textContent =
                                  etiquetaCategoria.nombre_etiqueta;

                                labelElement.appendChild(inputElement);
                                labelElement.appendChild(spanElement);

                                // Verificar si esta etiqueta está asociada al post actual y marcar el checkbox correspondiente
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
                    console.log(error);
                  },
                });
              } else {
                mostrarModalDeAdvertencia("Seleccione al menos una etiqueta");
              }
            } else {
              mostrarModalDeAdvertencia("Seleccione al menos una categoria");
            }

            // if (selectedCategory.length > 0 && selectedTags.length > 0) {
            //   // $.ajax({
            //   //   url: "procesarInformacion/filters/filters.php",
            //   //   type: "POST",
            //   //   data: {
            //   //     categoryData: selectedCategory,
            //   //     tagsData: selectedTags,
            //   //     action: "filterPostsByTags",
            //   //   },
            //   //   success: function (response) {
            //   //     console.log(response);
            //   //     var pageWrapper = $(".row-cards");
            //   //     pageWrapper.empty();
            //   //     var data = JSON.parse(response);
            //   //     data.forEach(function (post) {
            //   //       var cardCol = $('<div class="col-sm-6 col-lg-4"></div>');
            //   //       var card = $(
            //   //         '<div class="card card-sm position-relative"></div>'
            //   //       );
            //   //       var deleteIcon = $(
            //   //         '<svg xmlns="http://www.w3.org/2000/svg" class="icon delete-icon position-absolute top-0 end-0 m-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="top: -10px;"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>'
            //   //       );
            //   //       var editIcon = $(
            //   //         '<svg xmlns="http://www.w3.org/2000/svg" class="icon edit-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line></svg>'
            //   //       );
            //   //       editIcon.css("cursor", "pointer");
            //   //       var anchor = $('<a href="#" class="d-block"></a>');
            //   //       var imageUrl =
            //   //         post.ubicacion_imagen_post &&
            //   //         post.ubicacion_imagen_post.trim() !== ""
            //   //           ? post.ubicacion_imagen_post
            //   //           : "../img/genericImagePost.jpg";
            //   //       var imageElement = $("<img>");
            //   //       imageElement.attr("src", imageUrl);
            //   //       imageElement.addClass("card-img-top");
            //   //       imageElement.css({
            //   //         width: "100%",
            //   //         height: "100%",
            //   //         "object-fit": "cover",
            //   //         "max-height": "200px",
            //   //       });
            //   //       anchor.append(imageElement);
            //   //       var cardBody = $(
            //   //         '<div class="card-body d-flex align-items-center justify-content-between"></div>'
            //   //       );
            //   //       var title = $(
            //   //         '<h4 class="mb-0" style="margin-left: 10px;">' +
            //   //           post.titulo_post +
            //   //           "</h4>"
            //   //       );
            //   //       editIcon.attr("data-id", post.id_post);
            //   //       deleteIcon.attr("data-id", post.id_post);
            //   //       cardBody.append(title);
            //   //       cardBody.append(editIcon);
            //   //       card.append(deleteIcon);
            //   //       card.append(anchor);
            //   //       card.append(cardBody);
            //   //       cardCol.append(card);
            //   //       // Agregar la tarjeta al contenedor principal (pageWrapper)
            //   //       pageWrapper.append(cardCol);
            //   //       //Generar funcionalidad al boton de borrado del post
            //   //       deleteIcon.on("click", function () {
            //   //         $("#deleteModal").modal("show");
            //   //         // Configurar el botón de confirmar borrado
            //   //         $("#confirmDeleteBtn")
            //   //           .off("click")
            //   //           .on("click", function () {
            //   //             $.ajax({
            //   //               url: "procesarInformacion/posts/posts.php",
            //   //               type: "POST",
            //   //               data: {
            //   //                 id_post: post.id_post,
            //   //                 action: "deletePost",
            //   //               },
            //   //               success: function (response) {
            //   //                 if (response == "true") {
            //   //                   mostrarModalExito("Deleted Post");
            //   //                   setTimeout(function () {
            //   //                     window.location.href = "index.php";
            //   //                   }, 2500);
            //   //                 } else {
            //   //                   mostrarModalDeAdvertencia(
            //   //                     "Could not delete the post"
            //   //                   );
            //   //                 }
            //   //               },
            //   //               error: function (xhr, status, error) {
            //   //                 console.error(error);
            //   //                 mostrarModalDeAdvertencia("Connection error");
            //   //               },
            //   //             });
            //   //             $("#confirmModal").modal("hide");
            //   //           });
            //   //       });
            //   //       //Generar funcionalidad al boton de edicion
            //   //       editIcon.on("click", function () {
            //   //         $.ajax({
            //   //           url: "procesarInformacion/posts/posts.php",
            //   //           type: "POST",
            //   //           data: {
            //   //             action: "getInfoUpdatePost",
            //   //             id_post: post.id_post,
            //   //           },
            //   //           success: function (response) {
            //   //             var data = JSON.parse(response);
            //   //             // Acceder a la información del post
            //   //             var postInfo = data["post_info"];
            //   //             // Acceder a las etiquetas asociadas al post
            //   //             var labels = data["etiquetas"];
            //   //             // Rellenar los campos con la información del post
            //   //             $("#title_post").val(postInfo["titulo_post"]);
            //   //             $("#content_post").val(
            //   //               postInfo["contenido_textual_post"]
            //   //             );
            //   //             $("#state").val(postInfo["id_estado_post"]);
            //   //             // Actualizar la imagen del post
            //   //             if (postInfo["ubicacion_imagen_post"] !== null) {
            //   //               $("#postImage").attr(
            //   //                 "src",
            //   //                 postInfo["ubicacion_imagen_post"]
            //   //               );
            //   //             } else {
            //   //               $("#postImage").attr(
            //   //                 "src",
            //   //                 "../img/genericUploadImage.jpg"
            //   //               );
            //   //             }
            //   //             // Rellenar la categoría del post
            //   //             $("#categoria").val(postInfo["id_categoria_post"]);
            //   //             // Modificar el título superior
            //   //             $("#superiorTitle").text("Editar Post");
            //   //             $("#imageModal").modal("show");
            //   //             // Recuperar las etiquetas asociadas al post
            //   //             var etiquetas = data["etiquetas"];
            //   //             // Recuperar las etiquetas de la categoría del post
            //   //             //Esta seccion del codigo permite que se muestren activas las etiquetas asociadas al post
            //   //             var etiquetasCategoria = data["etiquetas_categoria"];
            //   //             var formSelectgroup =
            //   //               document.querySelector(".form-selectgroup");
            //   //             if (etiquetas.length > 0) {
            //   //               formSelectgroup.innerHTML = "";
            //   //               etiquetasCategoria.forEach(function (
            //   //                 etiquetaCategoria
            //   //               ) {
            //   //                 var labelElement =
            //   //                   document.createElement("label");
            //   //                 labelElement.classList.add(
            //   //                   "form-selectgroup-item"
            //   //                 );
            //   //                 var inputElement =
            //   //                   document.createElement("input");
            //   //                 inputElement.type = "checkbox";
            //   //                 inputElement.name = "name";
            //   //                 inputElement.value =
            //   //                   etiquetaCategoria.nombre_etiqueta;
            //   //                 inputElement.dataset.id =
            //   //                   etiquetaCategoria.id_etiqueta;
            //   //                 inputElement.classList.add(
            //   //                   "form-selectgroup-input"
            //   //                 );
            //   //                 var spanElement = document.createElement("span");
            //   //                 spanElement.classList.add(
            //   //                   "form-selectgroup-label"
            //   //                 );
            //   //                 spanElement.textContent =
            //   //                   etiquetaCategoria.nombre_etiqueta;
            //   //                 labelElement.appendChild(inputElement);
            //   //                 labelElement.appendChild(spanElement);
            //   //                 // Verificar si esta etiqueta está asociada al post actual y marcar el checkbox correspondiente
            //   //                 var etiquetaAsociada = etiquetas.find(function (
            //   //                   etiqueta
            //   //                 ) {
            //   //                   return (
            //   //                     etiqueta.id_etiqueta ===
            //   //                     etiquetaCategoria.id_etiqueta
            //   //                   );
            //   //                 });
            //   //                 if (etiquetaAsociada) {
            //   //                   inputElement.checked = true;
            //   //                 }
            //   //                 formSelectgroup.appendChild(labelElement);
            //   //               });
            //   //             }
            //   //             //Esta seccion permite al modal saber que lo que se realizara sera una actualizacion
            //   //             $("#btn_post").attr("data_info", "update");
            //   //             $("#btn_post").attr("data_id_post", post.id_post);
            //   //           },
            //   //           error: function (xhr, status, error) {
            //   //             mostrarModalDeAdvertencia("An error has occurred");
            //   //           },
            //   //         });
            //   //       });
            //   //     });
            //   //   },
            //   //   error: function (xhr, status, error) {
            //   //     console.log(error);
            //   //   },
            //   // });
            // } else {
            //   mostrarModalDeAdvertencia("Ingrese una categoria o tag");
            // }
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
