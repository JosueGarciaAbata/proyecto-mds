//  en este archivo estan importaciones de funciones, la primera guarda lo del form para crear o editar, la segunda permite mostrar los portafolios y tambien consigue los datos para su edicion, la tercera se encarga de las habilidades, me voy en busca del gran quiza, por si las dudas tambn hay un archivo css conectado a portafolios, agregare un archivo de mi base, si se crea un portafolio tambn su carpeta, se elimina la anterior con los datos.

import saveEditPortafolio from "./guardar-portafolio.mjs";
import getEditPortafolio from "./show-portafolios.mjs";
import habilityFunctions from "./skills.mjs"; 
//  bueno, falta lo de habilidades, segun yo estaba bien la insercion, 
const d = document;
const $form = d.getElementById("form-portafolio");
const $btnAgregar= d.getElementById("btn_create");
// function showPort

d.addEventListener("DOMContentLoaded",e=>{
  getEditPortafolio.getMyPortafolios();
  
  $btnAgregar.addEventListener("click",()=>{
      if($form.getAttribute('data-id')){
        //  entonces es como si estuviese desde 0
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
        saveEditPortafolio.editPortafolio();
      }else{
        saveEditPortafolio.saveAll();
      }
      getEditPortafolio.cleanFiles(); 
    }
  });

const $btnFotoPerfil=$form.querySelector("#foto-perfil");

$btnFotoPerfil.addEventListener("change",e=>{
  getEditPortafolio.setImageInBox(e,$form.querySelector("#show-img-perfil"));
});

const $btnFotFondo=$form.querySelector("#foto-fondo");

$btnFotFondo.addEventListener("change",e=>{
  getEditPortafolio.setImageInBox(e,$form.querySelector("#show-img-fondo"));
});

$form.querySelector("#show-page-portfolio").addEventListener("click",()=>{
  console.log("funciona");
  //tomar id y consultar sus datos, input pueden estar modificados
  getPortfolioData();
});






