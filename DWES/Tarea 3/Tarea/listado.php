<?php
// PHP para conectar y obtener el listado de productos (se implementará después)
?>
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
    <div class="container my-5">
        
        <h1 class="text-center text-white mb-4">Gestión de Productos</h1>
        
        <a href="crear.php" class="btn btn-success mb-3">Crear</a>
        
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Detalle</th>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Bucle para cada producto:
                    // foreach ($productos as $producto) { 
                    ?>
                        <tr>
                            <td><a href="detalle.php?id=[CODIGO]" class="btn btn-info btn-sm">Detalle</a></td>
                            
                            <td>[CODIGO]</td> 
                            <td>[NOMBRE]</td>
                            
                            <td class="text-center">
                                <a href="update.php?id=[CODIGO]" class="btn btn-warning btn-sm me-2">Actualizar</a>
                                <a href="borrar.php?id=[CODIGO]" class="btn btn-danger btn-sm">Borrar</a>
                            </td>
                        </tr>
                    <?php 
                    // } // Fin del bucle PHP
                    ?>
                    <tr>
                        <td><a href="detalle.php?id=2" class="btn btn-info btn-sm">Detalle</a></td>
                        <td>2</td> 
                        <td>Acer AX3950 i5-650 4GB 15TB W7HP</td>
                        <td class="text-center">
                            <a href="update.php?id=2" class="btn btn-warning btn-sm me-2">Actualizar</a>
                            <a href="borrar.php?id=2" class="btn btn-danger btn-sm">Borrar</a>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
        
        </div>
</body>
</html>