<?php
use App\Votacion;
use Jaxon\Jaxon;
use function Jaxon\jaxon;

$jaxon = jaxon();

$jaxon->register(Jaxon::CALLABLE_CLASS, Votacion::class);

if($jaxon->canProcessRequest()) {
    $jaxon->processRequest();
    exit(); // IMPORTANTE
}