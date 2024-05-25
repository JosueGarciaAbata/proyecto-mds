const d=document;
//  no voy a usar el contexto asi q ta bn usar una flechita
const insertComentary = async (idPost,content)=> {
  //  ["id-post"],$data["content"]
  const formData = new FormData();
  formData.append("id-post",idPost);
  formData.append("content",content);
  console.log(formData);
  try {
      let res = await fetch("./procesarInformacion/comentary/rest-comentary.php", {
        method: "POST",
        body:formData
      });
      if (!res.ok) throw { status: res.status, statusText: res.statusText };
      let json = await res.json();
      console.log(json);
      // Verificar si la respuesta indica que el usuario no tiene portafolios actualmente
      if (json.status === "OK" && json.statusText === "El usuario no tiene portafolios actualmente.") {
        console.log('El usuario no tiene portafolios actualmente');
        /*
          // Recargar los comentarios del post
          cargarComentarios(idPost);
          // Limpiar el textarea después de enviar el comentario
          d.getElementById('comentario_<?php echo $post['id_post']; ?>').value = '';
        */   
        return true; // No hacer nada si el usuario no tiene portafolios actualmente
      }
  
      //show(json);
    } catch (err) {
      let message = err.statusText || "Ocurrió un error";
      console.error(`Error ${err.status}: ${message}`);
      return false;
    }    
}

const cargarPost=async ()=>{
    //traer datos
    try {
        let res = await fetch("./procesarInformacion/comentary/rest-comentary.php", {
          method: "GET",
        });
        if (!res.ok) throw { status: res.status, statusText: res.statusText };
        let json = await res.json();
        // Verificar si la respuesta indica que el usuario no tiene portafolios actualmente
        if (json.status === "OK" && json.statusText === "Post not exist") {
          console.log('No existen post actualmente');
          return; // No hacer nada si el usuario no tiene portafolios actualmente
        }

        show(json);
      } catch (err) {
        let message = err.statusText || "Ocurrió un error";
        console.error(`Error ${err.status}: ${message}`);
        throw err;
      }
}

function show(posts){
  const pageWrapper = d.querySelector(".post-container");
  console.log(posts);
  // Limpiar el contenido actual de pageWrapper antes de agregar nuevos posts
  pageWrapper.innerHTML = "";
  posts.forEach(post => {
    const postDiv = d.createElement('div');
    postDiv.className = 'col-sm-8 mb-4';
    
    let firstPart = `
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card-body" ${post.id_post}>
                        <p class="card-text">${post.nombre_usuario}</p>
                        <h5 class="card-title">${post.titulo_post}</h5>
                        <p class="card-text">${post.contenido_textual_post}</p>
                        <p class="card-text">Categoría: ${post.nombre_categoria}</p>
                        <p class="card-text">Etiquetas: ${post.etiquetas}</p>
                        ${post.ubicacion_imagen_post ? `<img src="${post.ubicacion_imagen_post}" class="img-fluid" alt="imagen-post">` : '<div class="empty-container"><div>'}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Comentarios</h5>`;
                console.log(post);
if(post.comentarios.length!==0){
  post.comentarios.forEach(comentario=>{
    let headerComment="";
    //if(comentario.nombre_usuario!==null){
    //  lo puse antes porq no se q paso pero se puso un id_usuario no valido, tal vez sea por el truty de php, y como no hay llave foranea pues.......
      const name=(comentario.nombre_usuario!=="")?comentario.nombre_usuario:"Anonimo";
      headerComment=`<span class="comment-user-name">${name}</span><br>`
    //}
    //  idComentario, {idUsuario->NOMBRE}, contenido
    firstPart+=`
    <div user-comment data-id=${comentario.id_comentario}>${headerComment}${comentario.contenido_comentario}
    </div>`;
  })
}
    let secondPart=`<form class="mt-3 comentario_form" data-id="${post.id_post}" data-user="${post.id_usuario_post}">
                    <div class="mb-3">Escribe un comentario
                        <label class="form-label">
                            <textarea class="form-control comentaryArea" name="contenido_comentario" rows="1"></textarea>
                        </label>
                    </div>
                    <input type="submit" value="Enviar comentario">
                </form>
                <button class="btn btn-primary mt-3 chargeComentary">Cargar Comentarios</button>
            </div>
        </div>
    `;
    postDiv.innerHTML=firstPart+secondPart;
    pageWrapper.appendChild(postDiv);
  })
  setComentaryListener();
}

const cargarComentarios = async function (post) {
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

const setComentaryListener=()=>{
    //  darle listener a toda carta
  const $cards=d.querySelectorAll(".comentario_form");
  $cards.forEach(post => {
    post.addEventListener('submit', function(event) {
        // Prevenir el comportamiento predeterminado del envío del formulario
        event.preventDefault();
        const $input=post.querySelector(".comentaryArea");
        console.log(`El id del post es: ${post.dataset.id}\nContenido: ${$input.value}`);
        // Llamar a tu función personalizada para manejar el envío del formulario
        if(insertComentary(post.dataset.id,$input.value)){
          //limpiar caja d texto
          $input.value="";
        };
    });
  });
}
//  cuando se cargue el html quiero que se ejecute una funcion
d.addEventListener("DOMContentLoaded",()=>{
    cargarPost();
    //  cargarComentarios(); Esta en la d arriba
    //  setComentaryListener();
});




