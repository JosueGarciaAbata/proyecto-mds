
import saveEditPortafolio from "./guardar-portafolio.mjs";
import getEditPortafolio from "./show-portafolios.mjs";
import habilityFunctions from "./skills.mjs"; 

const d = document;
const $form = d.getElementById("form-portafolio");
const $btnAgregar= d.getElementById("btn_create");


d.addEventListener("DOMContentLoaded",e=>{
  getEditPortafolio.getMyPortafolios();
  
  $btnAgregar.addEventListener("click",()=>{
      if($form.getAttribute('data-id')){
        $form.removeAttribute("data-id");
        getEditPortafolio.limpiarCaja();
      }
  });
  habilityFunctions.setForm("form-portafolio");
  habilityFunctions.habilityListener("addTechnicalSkill","deleteLastTechnicalSkill","technicalSkillInput","habilidades-Tecnicas");
  habilityFunctions.habilityListener("addSocialSkill","deleteLastSocialSkill","socialSkillInput","habilidades-Sociales");  
});



$form.addEventListener("submit", async e => {
    //  si se envia el form q tenemos evitamos el reenvio
    if (e.target === $form) {
      e.preventDefault();
      habilityFunctions.saveHability();
      //primero insertar las habilidades nuevas
      saveEditPortafolio.setPropertys($form,"titulo-portafolio","mensaje-bienvenida","estudios","sobre-mi","foto-perfil","foto-fondo","cv","habilidades-Tecnicas","habilidades-Sociales","proyectos");
      //luego continuar, si hay un id en el fotm entonces crear, sino editar
      const dataId = $form.getAttribute('data-id');
      //  actualizar card en ambos casos
      if(dataId){
        if(saveEditPortafolio.editPortafolio()) 
          getEditPortafolio.cleanFiles();
      }else{
        // todos los datos deben ser adecuados
        if(!saveEditPortafolio.itsValidFiles(
          d.getElementById("foto-perfil").files[0],
          d.getElementById("cv").files[0],
          d.getElementById("foto-fondo").files[0])
        ){
          // aqui toca enviar una alerta
          mostrarModalDeAdvertencia("Incorrect files");
          return;  
        }
        if(saveEditPortafolio.saveAll())
          getEditPortafolio.cleanFiles();
      }
      
    }
  });

const $btnFotoPerfil=$form.querySelector("#foto-perfil");

$btnFotoPerfil.addEventListener("change",e=>{
  if (saveEditPortafolio.itsValidFiles(e.target.files[0])) {
    getEditPortafolio.setImageInBox(e,$form.querySelector("#show-img-perfil"));
  } else {
    e.preventDefault();
    mostrarModalDeAdvertencia("Archivo invalido");
    $btnFotoPerfil.parentNode.replaceChild($btnFotoPerfil.cloneNode(true), $btnFotoPerfil);
    $btnFotoPerfil.value = "";
  }
});

const $btnFotFondo=$form.querySelector("#foto-fondo");

$btnFotFondo.addEventListener("change", e => {
  if (saveEditPortafolio.itsValidFiles(e.target.files[0])) {
    getEditPortafolio.setImageInBox(e, $form.querySelector("#show-img-fondo"));
  } else {
    e.preventDefault();
    mostrarModalDeAdvertencia("Archivo invalido");
    $btnFotFondo.parentNode.replaceChild($btnFotoPerfil.cloneNode(true), $btnFotFondo);
    $btnFotFondo.value = "";
  }
});

//saveEditPortafolio
$form.querySelector("#show-page-portfolio").addEventListener("click",()=>{
  //tomar id y consultar sus datos, input pueden estar modificados
  getEditPortafolio.getDataForPage();
});
