<?php
// Primero requerimos la conexión a la base de datos
require_once "conexion.php";

//como vamos a listar productos, creamos una array de productos vacío:
$productos=[];

try {
//preparamos la consulta
$consultaListado= "SELECT id, nombre FROM productos";
//ejecutamos la consulta
$stmt=$conexion->query($consultaListado);
//almacenar los productos en el array de productos como un array asociativo
$productos=$stmt->fetchAll((PDO::FETCH_ASSOC));
} catch(PDOException $e){
    //si hay error, mostramos mensaje
    die( "Error al listar los productos: " . $e->getMessage());
}?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        /* Estilo para imitar el fondo de tu ejemplo */
        body {
            background-color: #5abcb9; 
        }
    </style>
</head>
<body>
    <!--Agrupamos el contenido. Doy una clase al contenedor por costumbre, y luego otras dos clases para bootstrap
    Tres (3) clases diferentes aplicadas a ese elemento div
    contenedor_listado: clase propia para lógica propia
    container: clase de bootstrap para centrar y dar márgenes automáticos
    my-5: clase de bootstrap para margen vertical
    -->
    <div class="contenedor_listado container my-5">
        
    <!--Encabezado. Yo le doy clase encabezado, aunque use bootstrap por costumbre. -->
        <h1 class="encabezado text-center text-white mb-4">Gestión de Productos</h1>
    <!--Botón que lanza crear.php para crear productos-->
        <a href="crear.php" class="boton_crear btn btn-success mb-3">Crear</a>
    <!--Para que se ve bien en móviles, ya es una constante en bootstrap estos días-->
        <div class="table-responsive">

    <!--Tabla que va a contener los productos -->
            <table class="tabla_productos table table-dark table-striped table-hover align-middle">
                <!--Encabezado de la tabla-->
                <thead>
                    <tr>
                        <th scope="col">Detalle</th>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <!--Cuerpo de la tabla-->
                <tbody>
                    <!--Bucle para cada productoforeach ($productos as $producto)-->
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            
                            <td><a href="detalle.php?id=<?php echo $producto['id']?>" class="btn btn-info btn-sm">Detalle</a></td>
                            
                            <!--Aquí rellena los datos el bucle, a partir de los datos de la consulta-->
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            
                            <!--Botones de actualizar y borrar-->
                            <td class="text-center">
                            <a href="update.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm me-2">Actualizar</a>
                            <a href="borrar.php?id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm">Borrar</a>                            </td>
                        </tr>
                    <!--Fin del bucle-->
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        </div>
</body>
</html>