<?php

//Para no tener que requerir las clases una a una, usamos el autoload
require __DIR__ . '/../vendor/autoload.php';

// Importo la clase Jugador del namespace que creé.
use Clases\Jugador;

//Iniciamos faker
$faker = Faker\Factory::create('es_ES');

//bucle para crear 10 jugadores falsos (10 objetos que luego insertaremos en la BBDD)
for ($i=0;$i<10;$i++){
$jugador = new Jugador();

//se usan los nomrbres de los métodos correspondientes para faker
$jugador->nombre=$faker->firstName();
$jugador->apellidos=$faker->LastName();

// Ojo aquí: unique() me asegura que no repita el dorsal en esta ejecución, 
// así evito el error de clave duplicada en la BBDD.
$jugador->dorsal = $faker->unique()->numberBetween(1, 99);
$jugador->posicion=$faker->randomElement(['Portero', 'Defensa', 'Centrocampista', 'Delantero']);

//Una vez tenemos los valores cargados, lanzamos el método create.
$jugador->create();

}

echo "<h1>¡Éxito!</h1>";
echo "<p>Se han insertado 10 jugadores aleatorios en la base de datos.</p>";
echo "<a href='jugadores.php'>Ver lista de jugadores</a>";

?>