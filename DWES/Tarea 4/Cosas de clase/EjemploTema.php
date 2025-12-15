<?php
/**
 * Función para solicitar las credenciales al usuario (mediante el prompt nativo del navegador).
 */
function pedir()
{
    // 1. Envía el encabezado de autentificación y el nombre del dominio (AuthName).
    header('WWW-Authenticate: Basic Realm="Contenido Protegido"');
    
    // 2. Envía el código de estado 401 (No Autorizado) para que el navegador muestre el prompt.
    header('HTTP/1.0 401 Unauthorized');
    
    echo "Datos Incorrectos o Usuario NO reconocido!!!";
    die(); // Termina la ejecución del script.
}

// -----------------------------------------------------------------------
// LÓGICA PRINCIPAL DE AUTENTIFICACIÓN
// -----------------------------------------------------------------------

// 1. Comprueba si el usuario NO ha introducido credenciales.
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    pedir();
} else {
    // 2. Si se han enviado credenciales, comprueba si son INCORRECTAS.
    // Se asume que el usuario correcto es 'gestor' y la contraseña es 'secreto'.
    if ($_SERVER['PHP_AUTH_USER'] != 'gestor' || $_SERVER['PHP_AUTH_PW'] != 'secreto') {
        pedir();
    }
}
// Si el script llega hasta aquí, la autentificación es correcta.

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGs03+Hhxv8T/Q5PaXtkKtu6ug5T0eNV6gBiFeWPGFN9Muh0f23Q9Ifj h" 
        crossorigin="anonymous">
    <title>Ejemplo Tema 4</title>
</head>
<body style='background:gray'>
    <h3 class='text-center mt-3'>Directivas PHP_AUTH</h3>
    <div class='container mt-3'>
        <div class="card text-white bg-primary mb-3 m-auto" style="max-width: 22rem;">
            <div class="card-header font-weight-bold text-center">PHP_AUTH</div>
            <div class="card-body" style='font-size:1.2em'>
                
                <p class="card-text"><span class='font-weight-bold'>Usuario:</span>
                "<?php echo $_SERVER['PHP_AUTH_USER'] ?>"</p>

                <p class="card-text"><span class='font-weight-bold'>Contraseña:</span>
                "<?php echo $_SERVER['PHP_AUTH_PW'] ?>"</p>

                <p class="card-text"><span class='font-weight-bold'>Método Autentificación:</span>
                "<?php 
                    // Se utiliza el operador ternario para evitar el Warning si la clave no está definida.
                    echo isset($_SERVER['AUTH_TYPE']) ? $_SERVER['AUTH_TYPE'] : 'N/A';
                ?>"
                </p>
            </div>
        </div>
    </div>
</body>
</html>