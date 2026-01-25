<?php
// 1. Cargamos el autoloader de composer
require_once __DIR__ . '/../vendor/autoload.php';

use PHP2WSDL\PHPClass2WSDL;

// 2. Definimos la clase y la URL del servicio
$class = "Clases\\Operaciones";
$uri = "http://localhost/DWES/DWES/Tarea6/servidorSoap/servicioW.php";

try {
    // 3. Creamos el generador. 
    // IMPORTANTE: Si la clase no se carga, PHP2WSDL no verá los métodos.
    $wsdl = new PHPClass2WSDL($class, $uri);
    
    // 4. Generamos el WSDL 
    $wsdl->generateWSDL(true);
    
    // 5. Guardamos en la ruta que pide el enunciado 
    $wsdl->save(__DIR__ . '/../servidorSoap/servicio.wsdl');
    
    echo "## Archivo WSDL generado correctamente ";
    echo "<p>Ruta: servidorSoap/servicio.wsdl</p>";
    
} catch (Exception $e) {
    echo "Error al generar el WSDL: " . $e->getMessage();
}