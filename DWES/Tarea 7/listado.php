<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/acciones.php';

$jaxon = jaxon();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        table { width: 90%; margin: 20px auto; border-collapse: collapse; background: #343a40; color: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; border-bottom: 1px solid #454d55; text-align: left; }
        th { background: #23272b; }
        .fas.fa-star, .fas.fa-star-half-alt, .far.fa-star { color: white; margin-left: 2px; }
        /* Estilo específico para simular la captura del enunciado */
        .valoracion-container { background: #3c4146; padding: 10px; border-radius: 5px; }
    </style>
    <?php echo $jaxon->getJs() ?>
    <?php echo $jaxon->getScript() ?>
</head>
<body onload="jaxon_Votacion.pintarProductos()">
    <div style="width: 90%; margin: auto; display: flex; justify-content: space-between; align-items: center;">
        <h1>Listado de Productos</h1>
        <div>
            Usuario: <strong><?php echo $_SESSION['usuario']; ?></strong> | 
            <a href="logout.php" style="color: red;">Cerrar Sesión</a>
        </div>
    </div>

    <div id="contenido">
        </div>
</body>
</html>