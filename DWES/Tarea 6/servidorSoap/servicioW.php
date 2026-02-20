<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Ruta física al archivo que generamos en el paso anterior
$ficheroWsdl = __DIR__ . '/servicio.wsdl';

try {
    // Creamos el servidor usando el WSDL
    $servidor = new SoapServer($ficheroWsdl);
    
    // Le pasamos tu clase de siempre
    $servidor->setClass('Clases\Operaciones');
    
    $servidor->handle();
} catch (SoapFault $f) {
    die("Error en el servidor: " . $f->getMessage());
}   