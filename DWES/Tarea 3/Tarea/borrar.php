<?php
// PHP para conectar, obtener el ID de producto y ejecutar el borrado (se implementará después)
$id_borrado = "[ID DEL PRODUCTO BORRADO]"; // Placeholder para el código borrado
$borrado_exitoso = true; // Simula que la operación fue exitosa

// Después de la operación:
// header('Location:listado.php'); // Redirección si se usa otra lógica
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        
        <h1 class="text-center mb-4">Borrar Producto</h1>
        
        <?php if ($borrado_exitoso): ?>
            <div class="alert alert-success text-center mx-auto" role="alert" style="max-width: 500px;">
                Producto de Código: **<?php echo $id_borrado; [cite_start]?>** Borrado correctamente. [cite: 8]
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center mx-auto" role="alert" style="max-width: 500px;">
                Error al borrar el producto de Código: **<?php echo $id_borrado; ?>**.
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-3">
            <a href="listado.php" class="btn btn-secondary">Volver</a>
        </div>
        
    </div>
</body>
</html>