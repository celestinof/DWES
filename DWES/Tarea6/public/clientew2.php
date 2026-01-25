<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Importamos el Service que ha creado el generador
use Clases1\ClasesOperacionesService;

try {
    // Instanciamos el servicio (el WSDL ya está configurado dentro)
    $service = new ClasesOperacionesService();

    echo "<h1>Prueba con Clases Generadas (ClienteW2)</h1>";

    // 1. Probar getFamilias
    $familias = $service->getFamilias();
    echo "<h3>Listado de Familias:</h3>";
    echo "<ul>";
    // El generador a veces devuelve un objeto con la propiedad 'return'
    foreach ($familias as $f) {
        echo "<li> Familia: $f </li>";
    }
    echo "</ul>";

    // 2. Probar getPvp
    $precio = $service->getPvp(1);
    echo "<h3>Precio del producto 1:</h3>";
    echo "<p>$precio €</p>";

} catch (Exception $e) {
    echo "Error al conectar con el servicio: " . $e->getMessage();
}