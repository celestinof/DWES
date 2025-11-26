<!--4.- Errores y manejo de excepciones
Errores y Manejo de Errores Tradicional
Este enfoque se centra en cómo PHP detecta, clasifica y notifica los problemas..-->

<!-- El texto señala que existen errores causados por fallos de programación (como una sintaxis incorrecta)
  y fallos ajenos al programa (como la caída de un servidor de base de datos o 
  intentar acceder a un registro que ya no existe

  E_NOTICE	Avisos leves	Puede indicar un error, pero el script continúa.	Usar una variable no definida.
  E_WARNING	Advertencias	Puede indicar un error, pero el script continúa.	División por cero.
  E_ERROR	Errores fatales	Detiene la ejecución del script.	                Intentar llamar a una función inexistente.

).-->


<?php
//El comportamiento de PHP ante los errores se configura inicialmente en el archivo php.ini.
//  Los parámetros clave son:

//    error_reporting: Define qué tipos de errores se notificarán. Su valor se establece combinando las constantes de error usando operadores a nivel de bit (como E_ALL & ~E_NOTICE).
//  Por defecto, es E_ALL & ~E_NOTICE (todos los errores, excepto los avisos).

//    display_errors: Indica si los errores se muestran en la salida estándar (por ejemplo, en el navegador).
//    En su valor por defecto (On), hace que los mensajes se envíen a la salida estándar (y por lo tanto se muestren en el navegador). 
//    Se debe desactivar (Off) en los servidores que no se usan para desarrollo sino para producción.

// Función error_reporting(): Te permite cambiar el nivel de notificación en un momento específico, muy útil para suprimir avisos o advertencias que sabes que van a ocurrir, 
// como en el ejemplo de la división por cero:

// Desactivar notificaciones E_NOTICE y E_WARNING temporalmente
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$resultado = $dividendo / $divisor;
// Restaurar el valor normal
error_reporting(E_ALL & ~E_NOTICE);

//Función set_error_handler(): 
// Permite reemplazar el mecanismo de gestión de errores predeterminado de PHP por una función personalizada que tú definas. Esta función se ejecutará cada vez que se produzca un error del nivel especificado.
//La función gestora debe recibir al menos el nivel del error y el mensaje descriptivo.
//La función restore_error_handler() devuelve el control al manejador de errores original de PHP.

?>


<!--4.- EXCEPCIONES. MÉTODO MÁS MODERNO. Desde PHP 5..-->

<?php

//Clásico bloque de try-throw-catch, o solo try-catch.

try {
    if ($divisor == 0) {
        // Se lanza una nueva excepción si el divisor es cero
        throw new Exception("División por cero.");
    }
    $resultado = $dividendo / $divisor;
} catch (Exception $e) {
    // El bloque catch captura la excepción y muestra el mensaje
    echo "Se ha producido el siguiente error: " . $e->getMessage();
}

//Las extensiones modernas y orientadas a objetos, como PDO (PHP Data Objects, para bases de datos), utilizan el modelo de excepciones.
// Para que PDO lance excepciones en lugar de usar el sistema de errores tradicional
//  (silencio o advertencias), debes configurar el atributo de modo de error:

$conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Cuando ocurre un error (como un fallo en la conexión por una contraseña incorrecta), 
// PDO lanza una excepción de tipo PDOException, 
// que puedes capturar y manejar en un bloque catch.