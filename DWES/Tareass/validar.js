/**
 * Función para validar el Login.
 * Se ejecuta cuando pulsamos "Registrar" o "Entrar" en el index.php.
 */
function envForm() {
    // Pillo los valores de los inputs por su ID
    var usu = document.getElementById("usu").value;
    var pass = document.getElementById('pass').value;

    // Llamo a la función de Jaxon. 
    // Como está en la clase App\Validar, Jaxon la registra como jaxon_Validar.
    // Usamos {mode: 'synchronous'} para que el JS se espere a que PHP conteste 
    // antes de decidir si envía el formulario o no.
    var res = jaxon_Validar.vUsuario(usu, pass, {mode: 'synchronous'});

    // Si la clase Validar.php devolvió false, sacamos el aviso del enunciado
    if (res == false) {
        alert("¡¡¡ Credenciales Erróneas !!!");
    }

    // Si devuelve true, el formulario se envía y vamos al listado. 
    // Si devuelve false, el onsubmit recibe false y no hace nada.
    return res;
}

/**
 * Función para gestionar los votos en la tienda.
 * Recibe el array con los datos del formulario de la fila correspondiente.
 */
function envFormVoto(form) {
    // Llamamos al método miVoto de la clase Votacion en PHP.
    // Le pasamos la nota (cantidad), el ID del producto y el nombre del usuario.
    var res = jaxon_Votacion.miVoto(form['cantidad'], form['idPr'], form['idUs'], {mode: 'synchronous'});

    // Si PHP nos dice que ya existe un voto (devolvió false)...
    if (res == false) {
        alert("¡Ya has votado ese producto!");
    } else {
        // Si el voto se guardó bien, llamamos a pintarEstrellas para que 
        // se refresquen los dibujos y las medias de la tabla sin recargar la página.
        jaxon_Votacion.pintarEstrellas({mode: 'synchronous'});
    }

    return res;
}