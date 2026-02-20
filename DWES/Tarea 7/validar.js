/**
 * Función para validar el Login.
 */
function envForm() {
    var usu = document.getElementById("usu").value;
    var pass = document.getElementById("pass").value;
    
    // Enviamos los datos a PHP
    JaxonApp.Validar.vUsuario(usu, pass);
}

function envFormVoto(form) {
    // Como los datos ya vienen limpios, no necesitamos el .value
    var cantidad = form['cantidad'];
    var idPr = form['idPr'];
    var idUs = form['idUs'];

    // Los enviamos directos a PHP
    JaxonApp.Votacion.miVoto(cantidad, idPr, idUs);
}