<?php
require __DIR__ . '/../vendor/autoload.php';

// Importante: Usamos el namespace que definimos en src/Jugador.php
use Clases\Jugador;

//si se presiona el botón de crear nuevo jugador
if(isset($_POST['btnCrear'])){
 
    //Comprobar que nombre y apellidos no estén vacíos (Requisito del enunciado). Aunque creo que al ser required en el formulario no habría problema
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    if(strlen($nombre) == 0 || strlen($apellidos) == 0) {
        die("Error: Nombre y Apellidos son obligatorios.");
    }

    //se crea nuevo objeto nuevo jugador ($j)
    $j = new Jugador();

    //Se llama al método existeDorsal de jugador.php para ver si ya existe. En ese caso se para el proceso.
    if($j->existeDorsal($_POST['dorsal'])) die("Error: Dorsal repetido");

    //Si no existe el dorsal, procedemos a cargar los valores introducidos en el formulario en los atributos del objeto $j
    $j->nombre = $nombre;
    $j->apellidos = $apellidos;
    $j->dorsal = $_POST['dorsal'];
    $j->posicion = $_POST['posicion'];

    //Una vez cargados, lanzamos create() para grabarlo en nuestra BBDD
    $j->create();

    //Nos dirigimos de nuevo a jugadores.php
    header("Location: jugadores.php");
}
?>