function getCoordenadas() {
    var dir = document.getElementById('dir').value;
    
    if (dir.length < 4) {
        alert("La dirección es demasiado corta");
        return;
    }
    
    // Llamada directa y moderna a Jaxon
    jaxon_getCoordenadas(dir);
}

function ordenarEnvios(id) {
    var puntos = $("#" + id + " input:hidden").map(function () {
        return this.value;
    }).get().join("|");
    
    // Le pasamos los puntos y el ID a PHP para que Jaxon haga la redirección por nosotros
    jaxon_ordenarEnvios(puntos, id);
}

function jaxonRedirect(url) {
    window.location.href = url;
}