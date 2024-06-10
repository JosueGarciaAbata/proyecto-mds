const d = document;
let menuVisible = false;
const $nav = d.getElementById("nav");

const seleccionar = () => {
  // Ocultar el menu una vez que selecciona una opcion
  $nav.classList = "";
  menuVisible = false;
}

const mostrarOcultarMenu = () => {
  $nav.classList.toggle("responsive")
  menuVisible = !menuVisible;
}

d.addEventListener("click", ev => {
  switch (ev.target) {
    case ".main-menu-item":
      seleccionar();
      break;
    case "main-menu-nav":
      mostrarOcultarMenu();
      break;
  }
})
//  ni idea d q intento hacer el josue
window.onscroll = function () {
  //efectoHabilidades();
};

const showHabilities = function (habilidades, $contentBox) {
  if (habilidades && Array.isArray(habilidades)) {
    habilidades.forEach(habilidad => {
      // Crear un elemento div para la habilidad técnica
      const $skill = d.createElement("div");
      $skill.classList.add("skill");
      // Crear un elemento span para el texto de la habilidad
      const span = d.createElement("span");
      span.textContent = habilidad.nombre;
      // Agregar el elemento span al elemento div de la habilidad
      $skill.appendChild(span);
      // Agregar el elemento div de la habilidad a la fila
      $contentBox.appendChild($skill);
    });
  }
}



const showProject = function (project, $box) {

  const $proyecto = d.createElement("div"),
    $img = d.createElement("img"),
    $overlay = d.createElement("div"),
    $title = d.createElement("h3"),
    $p = d.createElement("p");

  $proyecto.classList.add("proyecto");
  $proyecto.id = project.id;
  $img.setAttribute("src", project.ubic_img);
  $overlay.classList.add("overlay");
  $title.textContent = project.title;
  $p.textContent = project.description;

  //insertarlos en sus cajitas
  $overlay.appendChild($title);
  $overlay.appendChild($p);
  $proyecto.appendChild($img);
  $proyecto.appendChild($overlay);
  $box.appendChild($proyecto);
}

const searchProjects = async function (idPortfolio) {
  const $box = d.querySelector(".galeria");
  try {
    // como un extra q luego puede eliminarse me traje fechas de inicio y final, junto a la categoria del proyecto
    const url = `../obtener_proyectos.php?id-portfolio=${encodeURIComponent(idPortfolio)}`;
    let res = await fetch(url, {
      method: "GET",
    });
    if (!res.ok)
      throw { status: res.status, statusText: res.statusText };
    json = await res.json();
    console.log(json);
    if (json && Array.isArray(json)) {
      json.forEach(project => {
        showProject(project, $box);
      });
    }

  } catch (err) {
    let message = err.statusText || "Ocurrió un error al consultar los datos del portafolio";
    console.error(`Error ${err.status}: ${message}`);
    throw err;
  }
}

const getDataUsser= async function(idPortfolio){
  try {
    // como un extra q luego puede eliminarse me traje fechas de inicio y final, junto a la categoria del proyecto
    const url = `../getDataUsser.php?id-portfolio=${encodeURIComponent(idPortfolio)}`;
    let res = await fetch(url, {
      method: "GET",
    });
    if (!res.ok)
      throw { status: res.status, statusText: res.statusText };
    json = await res.json();
    console.log(json);
    // no usa de la consulta las columnas: fecha_modificacion_portafolio, pero se puede usar, no se donde dejarlo
    d.getElementById("name-user").textContent = json.nombre_usuario;
    d.getElementById("about-me").textContent = `${json.sobre_mi_portafolio}. Estudie en ${json.educacion_portafolio}. ${json.mensaje_bienvenida_portafolio}`;
    d.getElementById("email-user").textContent = json.correo_electronico_usuario;
  } catch (err) {
    let message = err.statusText || "Ocurrió un error al consultar los datos del portafolio";
    console.error(`Error ${err.status}: ${message}`);
    throw err;
  }
}

const getPortfolioData = async function (idPortfolio) {
  //  send request
  const url = `./../../usuarioRegistrado/procesarInformacion/portafolios/rest-portafolio.php?id-portafolio=${encodeURIComponent(idPortfolio)}`;
  let json;
  try {
    let res = await fetch(url, {
      method: "GET",
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };
    json = await res.json();
    d.title = `Portfolio ${json.titulo_portafolio}`;
    
    d.getElementById("img-portfolio").setAttribute("src", `../${json.foto_portafolio}`);
    document.querySelector(".inicio").style.backgroundImage = `url(../${json.fondo_portafolio})`;
    d.getElementById("download-cv").href = `../${json.ubicacionCv_portafolio}`;
    const $habT = d.querySelector(".technique");
    const $habS = d.querySelector(".social");
    // mostrar habilidades
    showHabilities(json.habilidades.habilidadesTecnicas, $habT);
    showHabilities(json.habilidades.habilidadesSociales, $habS);
    // mostrar proyectos
  } catch (err) {
    let message = err.statusText || "Ocurrió un error al consultar los datos del portafolio";
    console.error(`Error ${err.status}: ${message}`);
    throw err;
  }

}

d.addEventListener("DOMContentLoaded", () => {
  const idPortfolio = (new URLSearchParams(window.location.search)).get('id-portafolio');
  getDataUsser(idPortfolio);
  getPortfolioData(idPortfolio);
  searchProjects(idPortfolio);
});
