<?php
// acciones.php
require_once __DIR__ . '/vendor/autoload.php';

use Jaxon\Jaxon;

$jaxon = \Jaxon\jaxon();

// REGISTRAMOS LA CARPETA COMPLETA. Así Jaxon entiende perfectamente el namespace "App"
$jaxon->register(Jaxon::CALLABLE_DIR, __DIR__ . '/src', ['namespace' => 'App']);

$jaxon->setOption('core.request.uri', 'index.php');
// Solo procesa si es una petición AJAX real. 
// al entrar en index.php, esto se salta y permite cargar el HTML.
if($jaxon->canProcessRequest()) {
    $jaxon->processRequest();
    exit; 
}
?>