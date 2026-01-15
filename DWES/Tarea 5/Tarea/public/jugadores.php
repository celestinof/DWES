<?php
require __DIR__ . '/../vendor/autoload.php';
use Philo\Blade\Blade;

// Importante: Sin esto fallará el 'new Jugador()'
use Clases\Jugador;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new Blade($views, $cache);

$obj = new Jugador();

// Lógica de borrado (Si llega id_borrar por la URL)
if(isset($_GET['id_borrar'])) {
    // Usamos el método delete pasando el id
    $obj->delete($_GET['id_borrar']);
    // Recargamos la página para limpiar la URL y actualizar la lista
    header("Location: jugadores.php");
    exit();
}

//mostramos los jugadores. Usamos el método mostrarJugadores() que devuelve un array (o colección de objetos)
$arrayJugadores = $obj->mostrarJugadores();

// Renderizamos la vista pasando el array de jugadores
echo $blade->view()->make('vjugadores', compact('arrayJugadores'))->render();
?>