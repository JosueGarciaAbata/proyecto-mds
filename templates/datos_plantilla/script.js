const d = document;
let menuVisible = false;
const seleccionar=()=> {
  // Ocultar el menu una vez que selecciona una opcion
  document.getElementById("nav").classList = "";
  menuVisible = false;
}

const mostrarOcultarMenu=()=> {
  if (menuVisible) {
    document.getElementById("nav").classList = "";
    menuVisible = false;
  } else {
    document.getElementById("nav").classList = "responsive";
    menuVisible = true;
  }
}

d.addEventListener("click",ev=>{
  switch(ev.target){
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


