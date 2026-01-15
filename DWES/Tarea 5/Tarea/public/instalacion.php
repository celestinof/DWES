<?php
require __DIR__ . '/../vendor/autoload.php';
use Philo\Blade\Blade;

// Configuramos las rutas de Blade
$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new Blade($views, $cache);

// Simplemente renderizamos la vista de instalación (que tendrá el botón para ir a crearDatos.php)
echo $blade->view()->make('vinstalacion')->render();
?>