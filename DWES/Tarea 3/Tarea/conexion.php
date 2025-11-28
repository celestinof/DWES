<?php
/* Creamos las variables de conextión a la base de datos.
No obstante, por mi experiencia, es mejor usar constantes para estos valores.
Así evitamos que se modifiquen accidentalmente y se caiga la conexión.
Así que en lugar de usar el $host="localhost"; etc como en los ejemplos del tema...*/
define("HOST", "localhost");
define("NOMBRE_DB", "proyecto");
define("USER", "root");
define("PASS", "");

//Creamos la cadena DSN que usaremos en la conexion
define("DSN","mysql:host=".HOST.";dbname=".NOMBRE_DB.";charset=utf8mb4");

//Antes de intentar la conexión, vamos a prepararnos para activar la captura de errores
//activando las excepciones en PDO.
$configuracion_pdo=[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];


//Conectamos a la base de datos.
try {

    //Intenta la conexión:
    $conexion= new PDO (DSN, USER, PASS,$configuracion_pdo);

    //En caso de error:
} catch (PDOException $e) {
    //Si hay un error, la conexion muere y mostramos el mensaje de error
    die ("Error de conexión: ".$e->getMessage());
}








