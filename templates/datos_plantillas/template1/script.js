let menuVisible = false;
// Funcion que oculta o muestra el menu

function mostrarOcultarMenu() {
  if (menuVisible) {
    document.getElementById("nav").classList = "";
    menuVisible = false;
  } else {
    document.getElementById("nav").classList = "responsive";
    menuVisible = true;
  }
}

function seleccionar() {
  // Ocultar el menu una vez que selecciona una opcion
  document.getElementById("nav").classList = "";
  menuVisible = false;
}

// Funcion que aplica las animaciones de las habilidades
function efectoHabilidades() {
  var skills = document.getElementById("skills");
  var distanciaSkills = window.innerHeight - skills.getBoundingClientRect().top;
  if (distanciaSkills >= 100) {
    let habilidades = document.getElementById("progreso");
    habilidades[0].classList.add("javascript");
  }
}

// Deteectar el scrolling
window.onscroll = function () {
  efectoHabilidades();
};
