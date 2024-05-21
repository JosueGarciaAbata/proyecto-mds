// foto, fondo, cv

//  

const enviarArchivo = async function (archivo, tipo) {
    const formData = new FormData();
    formData.append("archivo", archivo);
    formData.append("tipo", tipo);
    try {
        let res = await fetch("./procesarInformacion/portafolios/upload-file.php", {
            method: "POST",
            body: formData,
        });
        if (!res.ok) throw { status: res.status, statusText: res.statusText };

        let json = await res.json();
        console.log(json);
    } catch (err) {
        let message = err.statusText || "Ocurrió un error al subir el archivo";
        console.error(`Error ${err.status}: ${message}`);
    }
};