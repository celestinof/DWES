<?php
// Cargamos Jaxon y dependencias
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Coordenadas.php';

use Jaxon\Jaxon;

$jaxon = jaxon();

// Registramos las funciones
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'getCoordenadas');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios');

// Si Jaxon detecta la petición, limpia la "basura", procesa y se detiene
if($jaxon->canProcessRequest()) {
    ob_clean(); 
    $jaxon->processRequest();
    exit(); 
}

function getCoordenadas($dir)
{
    $resp = jaxon()->newResponse();
    $dir = trim($dir);
    
    if (strlen($dir) < 4) {
        //  Usamos call() para invocar el alert nativo de JS
        $resp->call('alert', 'La dirección es demasiado corta.');
        return $resp;
    }
    
    try {
        $c = new Coordenadas($dir);
        $coords = $c->getCoordenadas(); 
        
        $lat = $coords[0] ?? '';
        $lon = $coords[1] ?? '';
        $alt = $coords[2] ?? '0'; 

        $resp->assign('lat', 'value', $lat);
        $resp->assign('lon', 'value', $lon);
        $resp->assign('alt', 'value', $alt . " mts."); 
        
    } catch (Exception $e) {
        //  Usamos call() para los errores
        $resp->call('alert', 'Error de coordenadas: ' . addslashes($e->getMessage()));
    }

    return $resp;
}

function ordenarEnvios($puntos, $idLt)
{
    $resp = jaxon()->newResponse();
    
    if (strlen(trim($puntos)) == 0) {
        // 🚨 CAMBIO: Usamos call()
        $resp->call('alert', 'No hay puntos válidos para ordenar en esta lista.');
        return $resp;
    }
    
    $c = new Coordenadas();
    $datos = $c->ordenarEnvios($puntos);
    
    $url = "repartos.php?action=oEnvios&idLt=" . urlencode($idLt);
    foreach ($datos as $r) {
        $url .= '&pos[]=' . urlencode($r);
    }
    
   
    $resp->call('jaxonRedirect', $url);
    
    return $resp;
}