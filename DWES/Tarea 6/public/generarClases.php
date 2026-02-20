<?php
// 1. Utilizamos el autoload de composer
require '../vendor/autoload.php'; 

use Wsdl2PhpGenerator\Generator;
use Wsdl2PhpGenerator\Config;

$generator = new Generator();

try {
    $generator->generate(
        new Config([
            // Archivo WSDL local que generamos antes
            'inputFile' => '../servidorSoap/servicio.wsdl', 
            // Directorio donde vamos a generar las clases (según enunciado)
            'outputDir' => '../src/Clases1', 
            // Namespace que vamos a usar con ellas
            'namespaceName' => 'Clases1',
            // ESTA LÍNEA ES VITAL: Evita el error de count() en PHP 8
            'sharedTypes' => true 
        ])
    );
    echo "<h1>¡Éxito!</h1>";
    echo "Las clases se han generado correctamente en la carpeta <b>src/Clases1</b>.";
    echo "<br>Ya puedes borrar este archivo o crear el clienteW2.php.";
} catch (Exception $e) {
    echo "<h2>Error al generar:</h2> " . $e->getMessage();
}