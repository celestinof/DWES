<?php
require_once 'conexion.php'; 

$mensaje = null;
$producto_a_borrar = null; // Para mostrar el nombre en la confirmación

// =======================================================================
// A. Validación del ID y Obtención del Producto
// =======================================================================

if (!isset($_GET['id'])) {
    header('Location: listado.php');
    exit(); 
}
$producto_id = (int)$_GET['id'];

try {
    // 1. Obtener el nombre del producto (para el mensaje de confirmación)
    $stmt_sel = $conexion->prepare("SELECT nombre FROM productos WHERE id = ?");
    $stmt_sel->bindParam(1, $producto_id, PDO::PARAM_INT);
    $stmt_sel->execute();
    $producto_a_borrar = $stmt_sel->fetch();

    if (!$producto_a_borrar) {
        // Si el producto ya fue borrado o no existe
        $mensaje = "El producto con ID #{$producto_id} no fue encontrado o ya ha sido borrado.";
    }

} catch (PDOException $e) {
    die("Error al buscar el producto para confirmar: " . $e->getMessage());
}

// =======================================================================
// B. Lógica de Ejecución del DELETE (Si el formulario ha sido enviado)
// Usamos $_POST para saber que el usuario confirmó el borrado.
// =======================================================================
if (isset($_POST['confirmar_borrado']) && $producto_a_borrar) {

    try {
        $sql = "DELETE FROM productos WHERE id = ?"; 
        $stmt_del = $conexion->prepare($sql);
        $stmt_del->bindParam(1, $producto_id, PDO::PARAM_INT);
        $stmt_del->execute();
        
        // Si el borrado fue exitoso
        $mensaje = "Producto '{$producto_a_borrar['nombre']}' con ID #{$producto_id} borrado correctamente.";
        
        // Establecemos $producto_a_borrar a null para que NO se muestre el formulario de confirmación otra vez
        $producto_a_borrar = null; 

    } catch (PDOException $e) {
        $mensaje = "Error al intentar borrar el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5 text-center">
        <h1 class="mb-4">Gestión de Borrado</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info d-inline-block shadow-sm" role="alert">
                <?php echo $mensaje; ?>
            </div>
            <div class="mt-4">
                <a href="listado.php" class="btn btn-primary">Volver al Listado</a>
            </div>
        <?php elseif ($producto_a_borrar): ?>
            <div class="alert alert-danger d-inline-block shadow-sm p-4">
                <h2>⚠️ Advertencia de Borrado</h2>
                <p class="lead">¿Está seguro de que desea **BORRAR** el producto:</p>
                <p class="fs-4">"<?php echo $producto_a_borrar['nombre']; ?>" (ID #<?php echo $producto_id; ?>)</p>
                
                <form method="POST" action="borrar.php?id=<?php echo $producto_id; ?>">
                    <button type="submit" name="confirmar_borrado" class="btn btn-danger btn-lg mt-3 me-3">Sí, BORRAR permanentemente</button>
                    <a href="listado.php" class="btn btn-secondary btn-lg mt-3">Cancelar y Volver</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>