const d = document;
let $form;
const setForm=form=>$form=d.getElementById(form);
const habilityListener = (nameBtnAdd, nameBtnDelete, inputHability, select) => {
  const $btnAddHability = d.getElementById(nameBtnAdd);
  const $btnDeleteHability = d.getElementById(nameBtnDelete);
  const $hability = d.getElementById(inputHability);
  const $select = d.getElementById(select);

  const addHability = () => {
    const newOption = $hability.value.trim();
    // Seleccionar todas las opciones del select
    const options = $form.querySelectorAll("option");
    // Comprobar si ya existe una opción con el mismo texto
    const optionExists = Array.from(options).some(option => option.text === newOption);
    if (newOption && !optionExists) {
      const option = document.createElement("option");
      option.value = -1;
      option.text = newOption;
      option.className = "added-skill";
      option.selected = true;
      $select.appendChild(option);
    }
    //limpiar
    $hability.value = "";
  }

  const deleteHability = () => {
      const options = $select.querySelectorAll("option.added-skill");
      if (options.length > 0) {
          const lastOption = options[options.length - 1];
          lastOption.parentNode.removeChild(lastOption);
      }
  }

  // Si todo es correcto entonces activa el listener
  if ($btnAddHability && $btnDeleteHability && $hability && $select) {
      $btnAddHability.addEventListener("click", addHability);
      $btnDeleteHability.addEventListener("click", deleteHability);
  }

}

const saveHability = async () => {
  const options = $form.querySelectorAll("select option.added-skill");
  //  filtrar las nuevas
  const newOptions = Array.from(options).filter(option => option.value === '-1');

  const habilidades = {
    tecnicas: [],
    sociales: []
  };

  newOptions.forEach(option => {
    const select = option.parentElement;
    const type = select.dataset.id;
    if (type === '0') {
      habilidades.tecnicas.push(option.text);
    } else if (type === '1') {
      habilidades.sociales.push(option.text);
    }
  });
  console.log(habilidades);
  const formData = new FormData();
  
  formData.append("skills", JSON.stringify(habilidades));

  try {
    let res = await fetch("./procesarInformacion/portafolios/skills.php", {
      method: "POST",
      body: formData,
    });
    if (!res.ok) throw { status: res.status, statusText: res.statusText };

    let json = await res.json();
    console.log(json);
    let i = 0;
    newOptions.forEach(option => {
      option.value = json["ids"][i];
      i++;
    });
    

  } catch (err) {
    let message = err.statusText || "Ocurrió un error al crear el portafolio";
    $form.insertAdjacentHTML(
      "afterend",
      `<p><b>Error ${err.status}: ${message}</b></p>`
    );
  }
};


const habilityFunctions = { habilityListener, setForm, saveHability};

export default habilityFunctions;