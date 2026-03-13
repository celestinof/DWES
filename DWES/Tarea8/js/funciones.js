/**
 * Archivo de funciones JavaScript para la gestión de envíos.
 * Se utiliza la librería Jaxon (v4) para la comunicación asíncrona con el servidor.
 */

/**Captura la dirección del formulario y solicita las coordenadas al backend.
 */
function getCoordenadas() {
    // Obtenemos el valor del input de dirección
    var dir = document.getElementById('dir').value;
    
    // Validación previa en cliente para evitar peticiones innecesarias al servidor
    if (dir.length < 4) {
        alert("La dirección es demasiado corta");
        return;
    }
    
    /** * Ejecuta la función PHP registrada en Jaxon. 
     * Jaxon genera automáticamente el prefijo 'jaxon_' para las funciones del lado del servidor.
     */
    jaxon_getCoordenadas(dir);
}

/**
 * Recopila los puntos de entrega de una lista y solicita su ordenación optimizada.
 * @param {string} id - El ID de la lista de tareas de Google.
 */
function ordenarEnvios(id) {
    /**
     * Utilizamos jQuery para seleccionar todos los inputs ocultos dentro del tbody de la lista.
     * Estos inputs contienen las coordenadas (lat,lon) de cada tarea.
     * .map(): Extrae el valor de cada input.
     * .join("|"): Crea una cadena única parsear en PHP.
     */
    var puntos = $("#" + id + " input:hidden").map(function () {
        return this.value;
    }).get().join("|");
    
    // Enviamos la cadena de puntos y el ID de la lista al servidor mediante Jaxon
    jaxon_ordenarEnvios(puntos, id);
}

/**
 * Función auxiliar para gestionar redirecciones desde el servidor.*/
function jaxonRedirect(url) {
    window.location.href = url;
}