<?php
require_once __DIR__ . '/../vendor/autoload.php';

// URL del archivo WSDL (el manual)
$urlWsdl = "http://localhost/DWES/DWES/Tarea6/servidorSoap/servicio.wsdl";

try {
    // Al pasarle el WSDL, el cliente ya sabe qué funciones existen y dónde está el servidor
    $cliente = new SoapClient($urlWsdl);

    // Probamos una función
    $familias = $cliente->getFamilias();

    echo "<h2>Probando Cliente con WSDL</h2>";
    echo "Familias encontradas: " . implode(", ", $familias);

} catch (SoapFault $e) {
    echo "Error: " . $e->getMessage();
}