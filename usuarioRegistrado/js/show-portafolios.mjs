const d = document;
let $formPortafolio = d.getElementById("form-portafolio");
//  console.log($formPortafolio);

let addListenerEditOrDelete = async function () {
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
        if (deletePortafolio(id)) {
          $card.remove();
        }
      }
      // Verifica si el evento proviene de la imagen con la clase 'edit-icon'
      else if (!e.target.classList.contains("edit-icon")) {
        console.log("Se hizo clic en la imagen con la clase edit-icon");
        getPortfolioData(id);
      }

    }
  });
}


let deletePortafolio = async function (portafolio) {
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

    if (json.status === "OK") {
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

let getMyPortafolios = async function () {
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
  const pageWrapper = d.querySelector(".row-cards");
  //  console.log(data);
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
  const imageUrl =
    portafolio.foto_portafolio != null &&
      portafolio.foto_portafolio.trim() !== ""
      ? portafolio.foto_portafolio
      : "../img/genericImagePost.jpg";
  imageElement.src = imageUrl;
  imageElement.className = "card-img-top";
  imageElement.setAttribute("data-bs-toggle", "modal");
  imageElement.setAttribute("data-bs-target", "#portfoliosModal");

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

const checkSelect = function ($select, habilidades) {
  //  console.log($select.options);
  //  console.log($select.options);
  Array.from($select.options).forEach(option => {
    //  TypeError: habilidades.some is not a function
    if (habilidades.some(habilidad => habilidad.id == option.value)) {
      option.selected = true;
    }
  });
};


const getPortfolioData = async function (idPortfolio) {
  //  send request
  const url = `./procesarInformacion/portafolios/rest-portafolio.php?id-portafolio=${encodeURIComponent(idPortfolio)}`;

  try {
    let res = await fetch(url, {
      method: "GET",
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };
    let json = await res.json();
    //console.log(json);
    $formPortafolio.dataset.id = idPortfolio;
    const $titulo = $formPortafolio.querySelector("#titulo-portafolio");
    $titulo.value = json.titulo_portafolio;
    const $mensaje = $formPortafolio.querySelector("#mensaje-bienvenida");
    $mensaje.value = json.mensaje_bienvenida_portafolio;
    const $estudios = $formPortafolio.querySelector("#estudios");
    $estudios.value = json.educacion_portafolio;
    const $sobreMi = $formPortafolio.querySelector("#sobre-mi");
    $sobreMi.value = json.sobre_mi_portafolio;
    const $selectHabT = $formPortafolio.querySelector("#habilidades-Tecnicas");
    checkSelect($selectHabT, json.habilidades.habilidadesTecnicas);
    const $selectHabS = $formPortafolio.querySelector("#habilidades-Sociales");
    checkSelect($selectHabS, json.habilidades.habilidadesSociales);
    const $selectProyectos = $formPortafolio.querySelector("#proyectos");
    checkSelect($selectProyectos, json.proyectos);

    const defaultImage = "../img/genericUploadImage.jpg";
    //  toca crear una cajita para q se vean las imagenes y el cv
    const $fotoPerfil = $formPortafolio.querySelector("#foto-perfil");
    $fotoPerfil.removeAttribute("required");
    const $fPerfilBox = $formPortafolio.querySelector("#show-img-perfil");
    let srcContent = (json.foto_portafolio) ? json.foto_portafolio : defaultImage;
    $fPerfilBox.setAttribute("src", srcContent);
    $fotoPerfil.value = "";
    const $fotoFondo = $formPortafolio.querySelector("#foto-fondo");
    $fotoFondo.removeAttribute("required");
    const $fFondoBox = $formPortafolio.querySelector("#show-img-fondo");
    srcContent = (json.fondo_portafolio) ? json.fondo_portafolio : defaultImage;
    $fFondoBox.setAttribute("src", srcContent);
    $fotoFondo.value = "";
    const $cvBox = $formPortafolio.querySelector("#cv");
    $cvBox.removeAttribute("required");
    $cvBox.value = "";
    //  enviar a un listener para q pueda ver si hubieron cambios, si es un si compararlo luego con la info del json cada vez q modifiq algo, si pasa eso toca modificarlo
  } catch (err) {
    let message = err.statusText || "Ocurrió un error al consultar los datos del portafolio";
    console.error(`Error ${err.status}: ${message}`);
    throw err;
  }
  //  change values 


}

const cleanFiles=()=>{
  // Limpia los campos de tipo file
  $formPortafolio.querySelector("#foto-perfil").value = "";
  $formPortafolio.querySelector("#foto-fondo").value = "";
  $formPortafolio.querySelector("#cv").value = "";
};

const uncheckSelect=($select)=>{
  Array.from($select.options).forEach(option => {
      option.selected = false;
  });
};

const limpiarCaja = () => {
  let defectImage = "../img/genericUploadImage.jpg";
  
  // Elimina el atributo "data-id" del formulario
  $formPortafolio.removeAttribute("data-id");
  // quitar lo q este en titulo y mensaje
  
  $formPortafolio.querySelector("#titulo-portafolio").value = '';
  $formPortafolio.querySelector("#mensaje-bienvenida").value = '';
  // Configura los campos como requeridos
  $formPortafolio.querySelector("#foto-perfil").setAttribute("required", "true");
  $formPortafolio.querySelector("#foto-fondo").setAttribute("required", "true");
  $formPortafolio.querySelector("#cv").setAttribute("required", "true");

  // Restablece las imágenes de perfil y fondo a la imagen por defecto
  $formPortafolio.querySelector("#show-img-perfil").setAttribute("src", defectImage);
  $formPortafolio.querySelector("#show-img-fondo").setAttribute("src", defectImage);
  cleanFiles();

  // clean check-box
    uncheckSelect($formPortafolio.querySelector("#habilidades-Tecnicas"));
    uncheckSelect($formPortafolio.querySelector("#habilidades-Sociales"));
    uncheckSelect($formPortafolio.querySelector("#proyectos"));
};



const setImageInBox = (ev,$element) => {
  const file = ev.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      $element.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

const getEditPortafolio = { getMyPortafolios,setImageInBox,limpiarCaja,cleanFiles };

export default getEditPortafolio;