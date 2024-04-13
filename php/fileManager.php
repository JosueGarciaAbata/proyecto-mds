<?php
//  esta clase solo va a crear o mover archivos pero cuando se recuperen se va a usar la ruta de la db

class FileManager
{
    private static $location = "Files";
    public static function getLocation()
    {
        return self::$location;
    }
    //  subyacentes
    public static function crearDirectorio($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
    //  para el principal
    public static function createDir()
    {
        // Ver si hay una carpeta para dicha carrera, sino la creamos
        self::crearDirectorio(self::getLocation());
    }
    //  add UsuarioFolder
    public static function añadirCarpetaUsuario($id)
    {
        //  crear d golpe todos

        $path = self::$location . "/" . $id;
        //  para su portafolio y otro para sus vlogs
        self::crearDirectorio($path . "/portafolio");
        self::crearDirectorio($path . "/vlogs");
    }

    public static function contarCarpetas($pathRelativo)
    {
        // Construir la ruta absoluta
        $pathAbsoluto = __DIR__ . '/' . $pathRelativo;

        // Verificar que el path sea un directorio
        if (!is_dir($pathAbsoluto)) {
            return 0; // Si no es un directorio, retornar 0
        }

        // Contador para almacenar el número de carpetas
        $contador = 0;

        // Iterar sobre los elementos del directorio
        $directorios = glob($pathAbsoluto . '/*', GLOB_ONLYDIR);
        foreach ($directorios as $directorio) {
            $contador++; // Incrementar el contador por cada directorio encontrado
        }

        return $contador; // Retornar el número total de carpetas
    }

    public static function añadirProyecto($id)
    {

        $path = self::$location . "/" . $id . "/portafolio/project";
        $nElement = (self::contarCarpetas($path) + 1);

        //images
        self::crearDirectorio($path . $nElement . "/images");
        //links
        // self::crearDirectorio($path."/links");
    }

    public static function guardarArchivos($path, $name, $id)
    {
        if (isset($_FILES[$name]) && $_FILES[$name]["error"] == UPLOAD_ERR_OK) {
            // Verificar si el archivo no está vacío y tambien si no hay errores al subir X archivo
            if ($_FILES[$name]["size"] > 0) {
                // Directorio donde se guardará el archivo
                $directorioDestino = $path;
                // Nombre del archivo en el servidor
                $extension = pathinfo(basename($_FILES[$name]["name"]), PATHINFO_EXTENSION);
                //  id del archivo
                $nombreArchivo = "/" . $id . $extension;
                // Ruta completa del archivo en el servidor
                $rutaArchivo = $directorioDestino . "" . $nombreArchivo;
                // Mover el archivo al directorio de destino
                if (move_uploaded_file($_FILES[$name]["tmp_name"], $rutaArchivo)) {
                    echo "El archivo se cargó correctamente en $rutaArchivo";
                } else {
                    echo "Error al mover el archivo.";
                }
            } else {
                echo "El archivo está vacío.";
            }
        } else {
            echo "No se envió ningún archivo o hubo un error en la carga.";
        }
    }


}

