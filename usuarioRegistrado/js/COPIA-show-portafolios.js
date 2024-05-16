const d = document;

d.addEventListener("DOMContentLoaded", function () {
  function getPosts() {
    fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
      method: "GET",
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        console.log(data);

        // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
        pageWrapper.innerHTML = "";

        //Generar los contenedores con la informacion del post
        data.forEach(function (post) {
          var cardCol = d.createElement("div");
          cardCol.className = "col-sm-6 col-lg-4";
          var card = d.createElement("div");
          card.className = "card card-sm position-relative";

          // SVG de borrado en la esquina superior derecha
          var deleteIcon = d.createElementNS(
            "http://www.w3.org/2000/svg",
            "svg"
          );
          deleteIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
          deleteIcon.setAttribute(
            "class",
            "icon delete-icon position-absolute top-0 end-0 m-3"
          );
          deleteIcon.setAttribute("width", "24");
          deleteIcon.setAttribute("height", "24");
          deleteIcon.setAttribute("viewBox", "0 0 24 24");
          deleteIcon.setAttribute("stroke-width", "2");
          deleteIcon.setAttribute("stroke", "currentColor");
          deleteIcon.setAttribute("fill", "none");
          deleteIcon.setAttribute("stroke-linecap", "round");
          deleteIcon.setAttribute("stroke-linejoin", "round");
          deleteIcon.setAttribute("style", "top: -10px;");
          deleteIcon.innerHTML =
            '<path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>';
          deleteIcon.addEventListener("click", function () {
            document.getElementById("deleteModal").modal("show");

            // Configurar el botón de confirmar borrado
            document
              .getElementById("confirmDeleteBtn")
              .addEventListener("click", function () {
                fetch("procesarInformacion/posts/posts.php", {
                  method: "POST",
                  body: JSON.stringify({
                    id_post: post.id_post,
                    action: "deletePortafolio",
                  }),
                })
                  .then(function (response) {
                    return response.text();
                  })
                  .then(function (response) {
                    if (response == "true") {
                      mostrarModalExito("Deleted Portafolio");
                      setTimeout(function () {
                        window.location.href = "index.php";
                      }, 2500);
                    } else {
                      mostrarModalDeAdvertencia("Could not delete the post");
                    }
                  })
                  .catch(function (error) {
                    console.error(error);
                    mostrarModalDeAdvertencia("Connection error");
                  });
                document.getElementById("confirmModal").modal("hide");
              });
          });

          // SVG de editar al extremo derecho del cardBody
          var editIcon = document.createElementNS(
            "http://www.w3.org/2000/svg",
            "svg"
          );
          editIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
          editIcon.setAttribute("class", "icon edit-icon");
          editIcon.setAttribute("width", "24");
          editIcon.setAttribute("height", "24");
          editIcon.setAttribute("viewBox", "0 0 24 24");
          editIcon.setAttribute("stroke-width", "2");
          editIcon.setAttribute("stroke", "currentColor");
          editIcon.setAttribute("fill", "none");
          editIcon.setAttribute("stroke-linecap", "round");
          editIcon.setAttribute("stroke-linejoin", "round");
          editIcon.innerHTML =
            '<path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line>';
          editIcon.style.cursor = "pointer"; // Establecer un cursor de puntero en el ícono para indicar que es interactivo
          editIcon.addEventListener("click", function () {
            fetch("procesarInformacion/posts/posts.php", {
              method: "POST",
              body: JSON.stringify({
                action: "getInfoUpdatePost",
                id_post: post.id_post,
              }),
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                var postInfo = data["post_info"];
                var labels = data["etiquetas"];
                // Resto del código de edición...
              })
              .catch(function (error) {
                mostrarModalDeAdvertencia("An error has occurred");
              });
          });

          var anchor = document.createElement("a");
          anchor.href = "#";
          anchor.className = "d-block";

          var imageElement = document.createElement("img");
          var imageUrl =
            post.ubicacion_imagen_post != null &&
            post.ubicacion_imagen_post.trim() !== ""
              ? post.ubicacion_imagen_post
              : "../img/genericImagePost.jpg";
          imageElement.src = imageUrl;
          imageElement.className = "card-img-top";
          imageElement.style.width = "100%";
          imageElement.style.height = "100%";
          imageElement.style.objectFit = "cover";
          imageElement.style.maxHeight = "200px";

          anchor.appendChild(imageElement);

          var cardBody = document.createElement("div");
          cardBody.className =
            "card-body d-flex align-items-center justify-content-between";

          var title = document.createElement("h4");
          title.className = "mb-0";
          title.style.marginLeft = "10px";
          title.textContent = post.titulo_post;

          editIcon.setAttribute("data-id", post.id_post);
          deleteIcon.setAttribute("data-id", post.id_post);

          cardBody.appendChild(title);
          cardBody.appendChild(editIcon);
          card.appendChild(deleteIcon);
          card.appendChild(anchor);
          card.appendChild(cardBody);
          cardCol.appendChild(card);

          // Agregar la tarjeta al contenedor principal (pageWrapper)
          pageWrapper.appendChild(cardCol);
        });
      })
      .catch(function (error) {
        mostrarModalDeAdvertencia("An error has occurred");
      });
  }
  //Obtener los posts
  getPosts();
});

const getMyPortafolios =async function() {
  try {
    let res = await fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
      method: "GET",
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };
    let json = await res.json();
    show(json);
  } catch (err) {
    let message = err.statusText || "Ocurrió un error";
    console.error(`Error ${err.status}: ${message}`);
    throw err;
  }
}

function show(data) {
  const pageWrapper = document.querySelector(".row-cards");
  console.log(data);
  // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
  pageWrapper.innerHTML = "";
  //Generar los contenedores con la informacion del post
  data.forEach(representData(portafolio));

  //LISTENNER EDIT - SELECTw
  addListenerEditOrDelete();
  
}

function representData(portafolio) {
  const cardCol = d.createElement("div");
  //cardCol.className = `col-sm-6 col-lg-4 portafolio ${portafolio.id}`;
  cardCol.classList.add(
    "col-sm-6",
    "col-lg-4",
    "cardPortafolio",
    portafolio.id
  );
  const card = d.createElement("div");

  card.classList.add("cardPortafolio", "card-sm", "position-relative");
  // SVG de borrado en la esquina superior derecha
  const deleteIcon = d.createElementNS("http://www.w3.org/2000/svg", "svg");
  deleteIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  deleteIcon.setAttribute(
    "class",
    "icon delete-icon position-absolute top-0 end-0 m-3"
  );
  deleteIcon.setAttribute("viewBox", "0 0 24 24");
  deleteIcon.innerHTML =
    '<path stroke="none" d="M0 0h24v24H0z"></path><line x1="4" y1="7" x2="20" y2="7"></line><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>';
  //  LISTENER
  deleteIcon.addEventListener("click", function () {
    //  MOSTRAR MODAL PARA ELIMINAR
    d.getElementById("deleteModal").style.display="block";
    // Configurar el botón de confirmar borrado
    d.querySelectorAll("confirmDeleteBtn").addEventListener("click", function () {
      fetch("procesarInformacion/portfolios/rest-portafolio.php", {
        method: "DELETE",
        body: JSON.stringify({
          idPortafolio: portafolio.id,
        }),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (response) {
          if (response == "OK") {
            //  cambiar
            mostrarModalExito("Deleted Portafolio");
            setTimeout(function () {
              window.location.href = "index.php";
            }, 1500);
          } else {
            //  cambiar
            mostrarModalDeAdvertencia("Could not delete the post");
          }
        })
        .catch(function (error) {
          console.error(error);
          //  CAMBIAR
          mostrarModalDeAdvertencia("Connection error");
        });
      //  CAMBIAR
      d.querySelector("confirmModal").style.display="hidden";
    });
  });

  // SVG de editar al extremo derecho del cardBody
  var editIcon = d.createElementNS("http://www.w3.org/2000/svg", "svg");

  editIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  editIcon.setAttribute("class", "icon edit-icon");
  editIcon.setAttribute("viewBox", "0 0 24 24");
  editIcon.innerHTML =
    '<path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line>';
  // Establecer un cursor de puntero en el ícono para indicar que es interactivo

  //  LISTENER
  editIcon.addEventListener("click", function () {
    fetch("procesarInformacion/posts/posts.php", {
      method: "POST",
      body: JSON.stringify({
        action: "getInfoUpdatePost",
        id_post: post.id_post,
      }),
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        var postInfo = data["post_info"];
        var labels = data["etiquetas"];
        // Resto del código de edición...
      })
      .catch(function (error) {
        mostrarModalDeAdvertencia("An error has occurred");
      });
  });

  var anchor = d.createElement("a");
  anchor.href = "#";
  anchor.className = "d-block";

  var imageElement = d.createElement("img");
  var imageUrl =
    post.ubicacion_imagen_post != null &&
    post.ubicacion_imagen_post.trim() !== ""
      ? post.ubicacion_imagen_post
      : "../img/genericImagePost.jpg";
  imageElement.src = imageUrl;
  imageElement.className = "card-img-top";

  anchor.appendChild(imageElement);

  var cardBody = d.createElement("div");
  cardBody.className =
    "card-body d-flex align-items-center justify-content-between";

  var title = d.createElement("h4");
  title.className = "mb-0";
  title.textContent = post.titulo_post;
  //  seguir añadiendo
  editIcon.setAttribute("data-id", post.id_post);
  deleteIcon.setAttribute("data-id", post.id_post);
  cardBody.appendChild(title);
  cardBody.appendChild(editIcon);
  card.appendChild(deleteIcon);
  card.appendChild(anchor);
  card.appendChild(cardBody);
  cardCol.appendChild(card);
  // Agregar la tarjeta al contenedor principal (pageWrapper)
  pageWrapper.appendChild(cardCol);
}

function addListenerEditOrDelete() {
  const container = document.querySelector(".row-cards");
  // Agrega un event listener al contenedor padre
  container.addEventListener("click", function (event) {
    // Verifica si el objetivo del evento es un elemento con la clase 'cardPortafolio'
    if (event.target.classList.contains("cardPortafolio")) {
      // Verifica si el evento proviene de la imagen con la clase 'delete-icon'
      if (event.target.classList.contains("delete-icon")) {
        // Ejecuta una función cuando se hace clic en la imagen con la clase 'delete-icon'
        console.log("Se hizo clic en la imagen con la clase delete-icon");
        // Aquí puedes llamar a una función específica para eliminar el elemento correspondiente
        
        editPortafolio();
        
      }
      // Verifica si el evento proviene de la imagen con la clase 'edit-icon'
      else if (event.target.classList.contains("edit-icon")) {
        // Ejecuta una función cuando se hace clic en la imagen con la clase 'edit-icon'
        console.log("Se hizo clic en la imagen con la clase edit-icon");
        // Aquí puedes llamar a una función específica para editar el elemento correspondiente
        deletePortafolio();
      }
    }
  });
}


function editPortafolio(e){

}

function deletePortafolio(e){
  
}