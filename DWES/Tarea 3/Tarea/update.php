<?php
// PHP para obtener y validar el ID del producto (se implementará después)
// y consultar sus datos para rellenar el formulario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        
        <h1 class="text-center mb-4">Modificar Producto</h1>

        <form action="" method="Post" id="updateproduct" class="row g-3 p-4 border rounded shadow-sm">
            
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="[NOMBRE ACTUAL]" required>
            </div>
            
            <div class="col-md-6">
                <label for="nombrecorto" class="form-label">Nombre Corto</label>
                <input type="text" name="nombrecorto" id="nombrecorto" class="form-control" value="[NOMBRE CORTO ACTUAL]" required>
            </div>
            
            <div class="col-md-6">
                <label for="precio" class="form-label">Precio (€)</label>
                <input type="number" name="precio" id="precio" class="form-control" value="[PRECIO ACTUAL]" step="0.01" min="0" required>
            </div>

            <div class="col-md-6">
                <label for="familia" class="form-label">Familia</label>
                <select name="familia" id="familia" class="form-select" required>
                    <option value="[CODIGO FAMILIA ACTUAL]" selected>[NOMBRE FAMILIA ACTUAL]</option> 
                    <option value="OTRO CODIGO">Otra Familia</option>
                </select>
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="5" placeholder="Características detalladas del producto...">[DESCRIPCION ACTUAL]</textarea>
            </div>
            
            <div class="col-12">
                <label for="caracteristicas" class="form-label">Características</label>
                <textarea name="caracteristicas" id="caracteristicas" class="form-control" rows="8" placeholder="Listado de características técnicas (Opcional)">[CARACTERÍSTICAS ACTUALES]</textarea>
            </div>

            <div class="col-12 mt-4">
                <input type="hidden" name="id_producto" value="[ID DEL PRODUCTO ACTUAL]">
                <input type="submit" value="Modificar" name="modificar" class="btn btn-primary me-2"> 
                <a href="listado.php" class="btn btn-secondary">Volver</a>
            </div>
            
        </form>
    </div>
</body>
</html>