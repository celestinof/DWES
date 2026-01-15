<?php
require __DIR__ . '/../vendor/autoload.php';
use Philo\Blade\Blade;

// Las rutas: __DIR__ es 'public', así que subimos un nivel (..) y entramos en views o cache.
$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new Blade($views, $cache);

echo $blade->view()->make('vcrear')->render();
?>