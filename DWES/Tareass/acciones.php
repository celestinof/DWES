<?php
// acciones.php
require_once __DIR__ . '/vendor/autoload.php';

use Jaxon\Jaxon;

$jaxon = \Jaxon\jaxon();

// Registro de clases
$jaxon->register(Jaxon::CALLABLE_CLASS, \App\Validar::class);
$jaxon->register(Jaxon::CALLABLE_CLASS, \App\Votacion::class);

$jaxon->setOption('core.prefix.class', 'jaxon_');

// Solo procesamos si Jaxon detecta que le están hablando desde JavaScript
if($jaxon->canProcessRequest()) {
    $jaxon->processRequest();
}

?>