<?php

//conexión a la base de datos
require_once 'conexion.php';

// Comprobamos si el ID del producto viene en la URL ($_GET).
if (!isset($_GET['id'])) {
    
    // Si NO se proporciona el ID, redirigimos a listado.php y detenemos el script.
    header('Location: listado.php');
    exit(); 
}

// Convertimos el ID a entero
$producto_id = (int)$_GET['id'];
$producto = null; // Inicializamos la variable para el resultado de la consulta.

// Bloque try...catch para manejar errores de la base de datos
try {
    
    //a. Consulta SQL que obtiene todos los datos del producto y el nombre de la familia 
    $sql= "SELECT productos.*, familias.nombre AS nombre_familia 
    FROM productos 
    JOIN familias ON productos.familia = familias.cod
    WHERE productos.id = ?";

    // b. Preparamos la sentencia (Consulta Preparada)
    $stmt = $conexion->prepare($sql);

    // c. Vinculamos el parámetro ID con la variable $producto_id
    $stmt->bindParam(1, $producto_id);

    // d. Ejecutamos la consulta
    $stmt->execute();
    
    // e. Obtenemos UNA SOLA fila de resultado
    $producto = $stmt->fetch();

    // 5. Comprobamos si el ID existe en la BBDD
    if (!$producto) {
        // Si el ID existe en la URL pero no en la tabla, redirigimos
        header('Location: listado.php');
        exit(); 
    }

} catch (PDOException $e) {
    
    // f. Si hay un error de BBDD al ejecutar la consulta, detenemos la ejecución
    die("Error al obtener los detalles del producto: " . $e->getMessage());
}

?>


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            /* Color de fondo similar al de tu imagen: Azul verdoso claro */
            background-color: #5abcb9; 
        }
    </style>
</head>
<body>
    <!--Agrupamos el contenido-->
    <div class="container my-5">
        <!--Encabezado-->
        <h1 class="text-center mb-4">Detalle del Producto: <?php echo $producto['nombre']; ?></h1>
        <!--Cuadro con los detalles del producto-->
        <div class="card shadow-lg mx-auto" style="max-width: 600px;">
            
            <!--Cuerpo del cuadro-->
           <div class="card-body">
                
                <ul class="list-group list-group-flush">
                    
                    <li class="list-group-item"><strong>ID (Código):</strong> <?php echo $producto['id']; ?></li>
                    
                    <li class="list-group-item"><strong>Nombre Completo:</strong> <?php echo $producto['nombre']; ?></li>
                    
                    <li class="list-group-item"><strong>Nombre Corto:</strong> <?php echo $producto['nombre_corto']; ?></li>
                    
                    <li class="list-group-item">
                        <strong>Precio (PVP):</strong> 
                        <?php echo number_format($producto['pvp'], 2, ',', '.') . ' €'; ?>
                    </li>
                    
                    <li class="list-group-item"><strong>Familia:</strong> <?php echo $producto['nombre_familia']; ?></li>
                    
                    <li class="list-group-item">
                        <strong>Descripción:</strong> 
                        <p class="mt-2 text-muted"><?php echo $producto['descripcion']; ?></p>
                    </li>
                    
                </ul>
            </div>
            
            </div> <div class="text-center mt-4">
            <a href="listado.php" class="btn btn-secondary me-2">Volver al Listado</a>
            
            <a href="update.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning">Editar Producto</a>
        </div>

    </div> </body>
</html>