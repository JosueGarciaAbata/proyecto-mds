const d = document;

const saveAll= async function saveAll($form){
    const titulo = d.getElementById("titulo-portafolio").value,
        mensaje = d.getElementById("mensaje-bienvenida").value,
        estudios = d.getElementById("estudios").value,
        sobreMi = d.getElementById("sobre-mi").value,
        fotoP = d.getElementById("foto-perfil").files[0],
        fotoF = d.getElementById("foto-fondo").files[0],
        cv = d.getElementById("cv").files[0],
        selectElementT = d.getElementById("habilidades-Tecnicas"),
        selectedOptionsT = Array.from(selectElementT.selectedOptions).map(
            (option) => option.value
        ),
        selectElementS = d.getElementById("habilidades-Sociales"),
        selectedOptionsS = Array.from(selectElementS.selectedOptions).map(
            (option) => option.value
        ),
        selectProjects = d.getElementById("proyectos"),
        selectedProjects=Array.from(selectProjects.selectedOptions).map(
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
        let message = err.statusText || "Ocurrió un error";
        $form.insertAdjacentHTML(
            "afterend",
            `<p><b>Error ${err.status}: ${message}</b></p>`
        );

    }
};


export default saveAll;


