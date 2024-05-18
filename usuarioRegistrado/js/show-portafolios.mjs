const d = document;

const editPortafolio=async function (portafolio) {

  const formData = new FormData();
  formData.append("id-portafolio", portafolio.id);
  console.log(formData);
  try {
    let res = await fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
        method: "PUT",
        body: formData,
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };

    let json = await res.json();
    console.log(json);


    //  ahora si a controlar la respuesta

    // Acceder a la información del post
    const portafolioInfo = json["post_info"];
    // Acceder a las etiquetas asociadas al post
    const labels = json["etiquetas"];

    // Rellenar los campos con la información del post
    document.getElementById("title_post").value = portafolioInfo["titulo_post"];
    document.getElementById("content_post").value =
      portafolioInfo["contenido_textual_post"];
    document.getElementById("state").value = portafolioInfo["id_estado_post"];

    // Actualizar la imagen del post
    if (portafolioInfo["ubicacion_imagen_post"] !== null) {
      document.getElementById("postImage").setAttribute(
        "src",
        portafolioInfo["ubicacion_imagen_post"]
      );
    } else {
      document.getElementById("postImage").setAttribute(
        "src",
        "../img/genericUploadImage.jpg"
      );
    }

    // Rellenar la categoría del post
    document.getElementById("categoria").value =
      portafolioInfo["id_categoria_post"];

    // Modificar el título superior
    document.getElementById("superiorTitle").textContent = "Editar Post";

    document.getElementById("imageModal").style.display = "block";

    // Recuperar las etiquetas asociadas al post
    const etiquetas = json["etiquetas"];

    // Recuperar las etiquetas de la categoría del post

    // Esta sección del código permite que se muestren activas las etiquetas asociadas al post
    const etiquetasCategoria = json["etiquetas_categoria"];
    const formSelectgroup = document.querySelector(".form-selectgroup");
    if (etiquetas.length > 0) {
      formSelectgroup.innerHTML = "";
      etiquetasCategoria.forEach(function (etiquetaCategoria) {
        const labelElement = document.createElement("label");
        labelElement.classList.add("form-selectgroup-item");

        const inputElement = document.createElement("input");
        inputElement.type = "checkbox";
        inputElement.name = "name";
        inputElement.value = etiquetaCategoria.nombre_etiqueta;
        inputElement.dataset.id = etiquetaCategoria.id_etiqueta;
        inputElement.classList.add("form-selectgroup-input");

        const spanElement = document.createElement("span");
        spanElement.classList.add("form-selectgroup-label");
        spanElement.textContent = etiquetaCategoria.nombre_etiqueta;

        labelElement.appendChild(inputElement);
        labelElement.appendChild(spanElement);

        // Verificar si esta etiqueta está asociada al post actual y marcar el checkbox correspondiente
        const etiquetaAsociada = etiquetas.find(function (etiqueta) {
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
} catch (err) {
    let message = err.statusText || "Ocurrió un error";
/*
    $form.insertAdjacentHTML(
        "afterend",
        `<p><b>Error ${err.status}: ${message}</b></p>`
    );
*/
    console.log(`${err.status}: ${message}`);
}

}


const deletePortafolio=async function(portafolio) {
  if (portafolio === null || portafolio === undefined || portafolio === '') {
    return; // Termina la función si la variable está vacía
  }
  const url = `./procesarInformacion/portafolios/rest-portafolio.php?id-portafolio=${encodeURIComponent(portafolio)}`;
  try {
    let res = await fetch(url, {
        method: "DELETE",
        headers: {
          'Content-Type': 'application/json'
        },
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };
    let json = await res.json();
    //console.log(json);
    if (json.status === "OK") {
      //  si va bn lo redirecciono a la pagina d portafolios, por ahora, que quiero que solo se elimine esa carta
      /*
      setTimeout(function () {
        window.location.href = "portfolios.php";
      }, 1500);
      */
      return true;
    } else {
      console.log(`${json.status}:${json.statusText}`);
      mostrarModalDeAdvertencia("No se pudo eliminar el portafolio");
    }
  } catch (err) {
      let message = err.statusText || "Ocurrió un error";
      mostrarModalDeAdvertencia("Error de conexión");
  }
    return false;
}

const getMyPortafolios = async function () {
  try {
    let res = await fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
      method: "GET",
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };
    let json = await res.json();

    // Verificar si la respuesta indica que el usuario no tiene portafolios actualmente
    if (json.status === "OK" && json.statusText === "El usuario no tiene portafolios actualmente.") {
      console.log('El usuario no tiene portafolios actualmente');
      return; // No hacer nada si el usuario no tiene portafolios actualmente
    }

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
  data.forEach(function (portafolio) {
    representData(portafolio, pageWrapper);
  });
  //LISTENNER EDIT - SELECT
  addListenerEditOrDelete();
}

function representData(portafolio, pageWrapper) {
  const $cardCol = d.createElement("div");
  //cardCol.className = `col-sm-6 col-lg-4 portafolio ${portafolio.id}`;
  $cardCol.classList.add(
    "col-sm-6",
    "col-lg-4",
    "cardPortafolio",
  );

  const $card = d.createElement("div");
  $card.classList.add("cardPortafolio", "card-sm", "position-relative");
  $card.dataset.id = portafolio.id_portafolio;
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
  //deleteIcon.addEventListener("click", deletePortafolio(portafolio));
  // SVG de editar al extremo derecho del cardBody
  const editIcon = d.createElementNS("http://www.w3.org/2000/svg", "svg");

  editIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
  editIcon.setAttribute("class", "icon edit-icon");
  editIcon.setAttribute("viewBox", "0 0 24 24");
  editIcon.innerHTML =
    '<path stroke="none" d="M0 0h24v24H0z"></path><path d="M11.933 5h-6.933v16h13v-8"></path><path d="M14 17h-5"></path><path d="M9 13h5v-4h-5z"></path><path d="M15 5v-2"></path><path d="M18 6l2 -2"></path><path d="M19 9h2"></path><line x1="7" y1="6" x2="7" y2="10"></line><line x1="17" y1="6" x2="17" y2="10"></line>';

  const anchor = d.createElement("a");
  anchor.href = "#";
  anchor.className = "d-block";
  //  modificar con la img q traigo
  const imageElement = d.createElement("img");
  var imageUrl =
    portafolio.foto_portafolio != null &&
      portafolio.foto_portafolio.trim() !== ""
      ? portafolio.foto_portafolio
      : "../img/genericImagePost.jpg";
  imageElement.src = imageUrl;
  imageElement.className = "card-img-top";

  anchor.appendChild(imageElement);

  const cardBody = d.createElement("div");
  cardBody.className =
    "card-body d-flex align-items-center justify-content-between";

  const title = d.createElement("h4");
  title.className = "mb-0";
  title.textContent = portafolio.titulo_portafolio;
  //  seguir añadiendo
  //editIcon.setAttribute("data-id", portafolio.id_portafolio);
  //deleteIcon.setAttribute("data-id", portafolio.id_portafolio);
  cardBody.appendChild(title);
  cardBody.appendChild(editIcon);
  $card.appendChild(deleteIcon);
  $card.appendChild(anchor);
  $card.appendChild(cardBody);
  $cardCol.appendChild($card);
  // Agregar la tarjeta al contenedor principal (pageWrapper)
  pageWrapper.appendChild($cardCol);
}

function addListenerEditOrDelete() {
  const container = document.querySelector(".row-cards");
  // Agrega un event listener al contenedor padre
  container.addEventListener("click", function (e) {
    
    // Verifica si el objetivo del evento es un elemento con la clase 'cardPortafolio'
    if (e.target.closest(".cardPortafolio")) {
      const $card = e.target.closest(".cardPortafolio");
      // const id = card.querySelector(".edit-icon").getAttribute("data-id");
      const id = $card.dataset.id;
      // Verifica si el evento proviene de la imagen con la clase 'delete-icon'
      if (e.target.classList.contains("delete-icon")) {
        // Ejecuta una función cuando se hace clic en la imagen con la clase 'delete-icon'
        console.log("Se hizo clic en la imagen con la clase delete-icon");
        // Si se elimina tambien eliminamos la carta
        if(deletePortafolio(id)){
          $card.remove();
        }
      }
      // Verifica si el evento proviene de la imagen con la clase 'edit-icon'
      else if (e.target.classList.contains("edit-icon")) {
        // Ejecuta una función cuando se hace clic en la imagen con la clase 'edit-icon'
        console.log("Se hizo clic en la imagen con la clase edit-icon");
        // Aquí puedes llamar a una función específica para editar el elemento correspondiente
        editPortafolio(id);
      }
    }
  });
}


export default getMyPortafolios;









