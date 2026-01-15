<?php
require __DIR__ . '/../vendor/autoload.php';

// Importante: Como le puse namespace a la clase, tengo que usarlo aquí para que la encuentre.
use Clases\Jugador;

$jugador = new Jugador();

// Comprobamos si hay datos en la tabla
if ($jugador->hayDatos()) {
    //Si hay datos, vamos a jugadores.php
    header("Location: jugadores.php");
} else {
    //Si no hay datos, vamos instalacion.php
    header("Location: instalacion.php");
}
exit();