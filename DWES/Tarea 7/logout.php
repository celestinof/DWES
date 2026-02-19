<?php
// Iniciamos la sesión para poder acceder a ella
session_start();

// Eliminamos todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, eliminamos también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruimos la sesión
session_destroy();

// Redirigimos al usuario al formulario de login (index.php)
header("Location: index.php");
exit;