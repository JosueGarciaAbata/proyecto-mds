
import saveEditPortafolio from "./guardar-portafolio.mjs";
import getEditPortafolio from "./show-portafolios.mjs";

const d = document;
const $form = d.getElementById("form-portafolio");
const $btnAgregar= d.getElementById("btn_create");
// function showPort

d.addEventListener("DOMContentLoaded",e=>{
  getEditPortafolio.getMyPortafolios();
  $btnAgregar.addEventListener("click",()=>{
      if($form.getAttribute('data-id')){
        $form.removeAttribute("data-id");
      }
  });
});



$form.addEventListener("submit", async e => {
    //  si se envia el form q tenemos evitamos el reenvio
    if (e.target === $form) {
      e.preventDefault();
      const dataId = $form.getAttribute('data-id');
      if(dataId){
        saveEditPortafolio.editPortafolio($form);
      }else{
        saveEditPortafolio.saveAll($form);
      }
    }
  });
