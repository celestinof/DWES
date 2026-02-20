<?php
// Lo primero es recuperar la sesión actual para poder cerrarla. 
// Si no hago session_start(), PHP no sabe qué sesión quiero borrar.
session_start();

// Compruebo si existe la variable de sesión 'usuario' (o 'usu' según lo que pusieras en Validar)
// Si existe, la borro con unset para que el usuario deje de estar "logueado".
if (isset($_SESSION['usuario'])) {
    unset($_SESSION['usuario']);
}

// Por si acaso, también podemos usar session_destroy() que borra todo rastro de la sesión.
session_destroy();

// Una vez que ya no hay sesión, mandamos al usuario de patitas a la calle.
// En nuestro caso, la pantalla de inicio es index.php.
header('Location: index.php');

// Pongo un exit para asegurarme de que el script se para aquí y no ejecute nada más por error.
exit();