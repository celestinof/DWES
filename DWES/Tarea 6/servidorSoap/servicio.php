<?php
// 1. Cargamos el autoload para que encuentre tus clases
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Usamos la clase Operaciones que acabas de crear
use Clases\Operaciones;

// 3. Definimos la URL de este servidor y un nombre para el servicio (uri)
// Ajusta esta URL si tu carpeta en htdocs tiene otro nombre
$url = "http://localhost/DWES/DWES/Tarea6/servidorSoap/servicio.php";
$uri = "http://localhost/DWES/DWES/Tarea6";

// 4. Preparamos las opciones: como no hay WSDL, el primer parámetro es null
$opciones = ['uri' => $uri];

try {
    // Creamos el objeto servidor de PHP
    $servidor = new SoapServer(null, $opciones);
    
    // Le decimos que use la clase Operaciones para responder
    $servidor->setClass('Clases\Operaciones');
    
    // Ponemos el servidor en marcha
    $servidor->handle();
    
} catch (SoapFault $error) {
    die("Error en el servidor: " . $error->getMessage());
}