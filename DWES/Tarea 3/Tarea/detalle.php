<?php
// PHP que obtendrá y mostrará el ID del producto (se implementará más tarde)
// Por ahora, solo es HTML y estructura
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
    <div class="container my-5">
        
        <h1 class="text-center text-white mb-4">Detalle Producto</h1>

        <div class="card p-4 mx-auto" style="max-width: 700px; background-color: #5abcb9; color: white; border: none;">
            
            <div class="d-flex justify-content-between mb-4">
                <h3 class="card-title text-uppercase">[NOMBRE DEL PRODUCTO]</h3>
                <h4 class="card-title">Código: [CÓDIGO]</h4>
            </div>

            <hr class="text-white">

            <div class="row">
                
                <div class="col-12 col-lg-6">
                    <p><strong>Nombre:</strong> <span class="text-white-75">[NOMBRE DEL PRODUCTO]</span></p>
                    <p><strong>Nombre Corto:</strong> <span class="text-white-75">[NOMBRE CORTO]</span></p>
                    <p><strong>Código:</strong> <span class="text-white-75">[CÓDIGO]</span></p>
                    <p><strong>PVP (€):</strong> <span class="text-white-75">[PVP]</span></p>
                    <p><strong>Código Familia:</strong> <span class="text-white-75">[CÓDIGO FAMILIA]</span></p>
                </div>
                
                <div class="col-12 mt-3">
                    <p><strong>Descripción:</strong></p>
                    <div class="p-3 rounded text-white-75" style="border: 1px solid rgba(255, 255, 255, 0.5);">
                        [DESCRIPCIÓN COMPLETA DEL PRODUCTO]
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="listado.php" class="btn btn-secondary btn-lg">Volver</a>
            </div>

        </div> </div>
</body>
</html>