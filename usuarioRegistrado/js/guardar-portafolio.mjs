const d = document;
let $form, $titulo,$mensaje,$studios, $sobreMi, $fotoP, $fotoF, $cv, $selectElementT, $selectElementS, $selectProjects;


const setPropertys= (form,titulo,mensaje,studios, sobreMi, fotoP, fotoF, cv, selectElementT, selectElementS, selectProjects)=>{
  $form=form;
  $titulo=d.getElementById(titulo);
  $mensaje=d.getElementById(mensaje);
  $studios=d.getElementById(studios);
  $sobreMi=d.getElementById(sobreMi);
  $fotoP=d.getElementById(fotoP);
  $fotoF=d.getElementById(fotoF);
  $cv=d.getElementById(cv);
  $selectElementT=d.getElementById(selectElementT);
  $selectElementS=d.getElementById(selectElementS);
  $selectProjects=d.getElementById(selectProjects);
};

const elementsNotExist = () => {
  //como un for o bueno un filter para ver si uno esta como nulo
  return [$form,$titulo, $mensaje, $studios, $sobreMi, $fotoP, $fotoF, $cv, $selectElementT, $selectElementS, $selectProjects].some(value => value == null);
};


const saveAll = async function saveAll() {
  if(elementsNotExist()){
    return;
  }
  const titulo = $titulo.value,
    mensaje = $mensaje.value,
    estudios = $studios.value,
    sobreMi = $sobreMi.value,
    fotoP = $fotoP.files[0],
    fotoF = $fotoF.files[0],
    cv = $cv.files[0],
    selectedOptionsT = Array.from($selectElementT.selectedOptions).map(
      (option) => option.value
    ),
    selectedOptionsS = Array.from($selectElementS.selectedOptions).map(
      (option) => option.value
    ),
    selectedProjects = Array.from($selectProjects.selectedOptions).map(
      (option) => option.value
    ),
    formData = new FormData();
    formData.append("titulo", titulo);
    formData.append("mensaje", mensaje);
    formData.append("estudios", estudios);
    formData.append("sobreMi", sobreMi);
    formData.append("fotoP", fotoP);
    formData.append("fotoF", fotoF);
    formData.append("cv", cv);

  selectedOptionsT.forEach((option) =>
    formData.append("habilidadesTecnicas[]", option)
  );
  selectedOptionsS.forEach((option) =>
    formData.append("habilidadesSociales[]", option)
  );
  selectedProjects.forEach((option) =>
    formData.append("proyectos[]", option)
  );
  console.log();
  try {
    let res = await fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
      method: "POST",
      body: formData,
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };

    let json = await res.json();
    console.log(json);
  } catch (err) {
    let message = err.statusText || "Ocurrió un error al crear el portafolio";
    $form.insertAdjacentHTML(
      "afterend",
      `<p><b>Error ${err.status}: ${message}</b></p>`
    );
  }
};

const editPortafolio = async function () {
  if(elementsNotExist()){
    return;
  }
  const titulo = $titulo.value,
    mensaje = $mensaje.value,
    estudios = $studios.value,
    sobreMi = $sobreMi.value,
    fotoP = $fotoP.files[0],
    fotoF = $fotoF.files[0],
    cv = $cv.files[0],
    selectedOptionsT = Array.from($selectElementT.selectedOptions).map(
      (option) => option.value
    ),
    selectedOptionsS = Array.from($selectElementS.selectedOptions).map(
      (option) => option.value
    ),
    selectedProjects = Array.from($selectProjects.selectedOptions).map(
      (option) => option.value
    ),
    formData = new FormData();
    formData.append("id", $form.dataset.id);
    console.log($form.dataset.id);
    formData.append("titulo", titulo);
    formData.append("mensaje", mensaje);
    formData.append("estudios", estudios);
    formData.append("sobreMi", sobreMi);

  if(fotoP){
    formData.append("fotoP", fotoP);
  }
  if(fotoF){
    formData.append("fotoF", fotoF);
  }
  if(cv){
    formData.append("cv", cv);
  }

  selectedOptionsT.forEach((option) =>
    formData.append("habilidadesTecnicas[]", option)
  );
  selectedOptionsS.forEach((option) =>
    formData.append("habilidadesSociales[]", option)
  );
  selectedProjects.forEach((option) =>
    formData.append("proyectos[]", option)
  );
  console.log(formData);
  try {
    let res = await fetch("./procesarInformacion/portafolios/rest-portafolio.php", {
      method: "POST",
      body: formData,
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };

    let json = await res.json();
    console.log(json);
  } catch (err) {
    let message = err.statusText || "Ocurrió un error al editar el portafolio";
    $form.insertAdjacentHTML(
      "afterend",
      `<p><b>Error ${err.status}: ${message}</b></p>`
    );
  }

};











const saveEditPortafolio = { setPropertys,saveAll, editPortafolio };

export default saveEditPortafolio;