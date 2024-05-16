
import saveAll from "./guardar-portafolio.mjs";
import getMyPortafolios from "./show-portafolios.mjs";
const d = document;
const $form = d.getElementById("form-portafolio");

// function showPort

d.addEventListener("DOMContentLoaded",e=>{
  getMyPortafolios();
});






$form.addEventListener("submit", async e => {
    //  si se envia el form q tenemos evitamos el reenvio
    if (e.target === $form) {
      e.preventDefault();
      saveAll($form);

    }
  });
/*
  d.addEventListener("click", async e => {
    if (e.target.matches(".edit")) {
      $title.textContent = "Editar Santo";
      $form.nombre.value = e.target.dataset.name;
      $form.constelacion.value = e.target.dataset.constellation;
      $form.id.value = e.target.dataset.id;
    }

    if (e.target.matches(".delete")) {
      let isDelete = confirm(`¿Estás seguro de eliminar el id ${e.target.dataset.id}?`);

      if (isDelete) {
        //Delete - DELETE
        try {
          let options = {
            method: "DELETE",
            headers: {
              "Content-type": "application/json; charset=utf-8"
            }
          },
          //  solo l añadimos el id a el
            res = await fetch(`http://localhost:3000/santos/${e.target.dataset.id}`, options),
            json = await res.json();

          if (!res.ok) throw { status: res.status, statusText: res.statusText };

          location.reload();
        } catch (err) {
          let message = err.statusText || "Ocurrió un error";
          alert(`Error ${err.status}: ${message}`);
        }
      }
    }
  })



*/