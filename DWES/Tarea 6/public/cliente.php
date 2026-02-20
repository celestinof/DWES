<?php
// También cargamos el autoload
require_once __DIR__ . '/../vendor/autoload.php';

// La dirección de donde está el servicio.php
$urlServidor = "http://localhost/DWES/DWES/Tarea6/servidorSoap/servicio.php";

// En el modo SIN WSDL, el cliente necesita saber la 'location' y la 'uri'
$opciones = [
    'location' => $urlServidor,
    'uri'      => "http://localhost/DWES/DWES/Tarea6"
];

try {
    // Creamos el cliente
    $cliente = new SoapClient(null, $opciones);

    // LLAMADA 1: Probar getFamilias
    $familias = $cliente->getFamilias();
    echo "<h2>Prueba del Servicio Web (Sin WSDL)</h2>";
    echo "<b>Lista de Familias:</b><br>";
    foreach ($familias as $f) {
        echo "- $f <br>";
    }

    // LLAMADA 2: Probar getPvp de un producto (pon el ID 1 o uno que exista)
    $precio = $cliente->getPvp(1);
    echo "<br><b>Precio del producto 1:</b> $precio €<br>";

    // LLAMADA 3: Probar getStock (Producto 1 en Tienda 1)
    $unidades = $cliente->getStock(1, 1);
    echo "<b>Stock del producto 1 en la tienda 1:</b> $unidades unidades";

} catch (SoapFault $error) {
    echo "Error: " . $error->getMessage();
}