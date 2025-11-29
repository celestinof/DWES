<?php

//Revisar toda, funciona mal.
// 1. Incluimos la conexión
require_once 'conexion.php'; 

// Inicializamos mensajes de control
$mensaje_error = null;
$mensaje_exito = null;

// =======================================================================
// A. Obtener el ID del producto y validar
// =======================================================================

// Comprobamos si el ID del producto viene en la URL ($_GET)
if (!isset($_GET['id'])) {
    
    // Si no hay ID, redirigimos inmediatamente.
    header('Location: listado.php');
    exit(); 
}

// Recogemos y saneamos el ID que viene de listado.php
$producto_id = (int)$_GET['id'];


// =======================================================================
// B. Obtener las familias para el select (igual que en crear.php)
// =======================================================================
$familias = [];

try {
    $stmt_fam = $conexion->query("SELECT cod, nombre FROM familias ORDER BY nombre");
    $familias = $stmt_fam->fetchAll();

} catch (PDOException $e) {
    // Es un error crítico, si no hay familias el formulario no funciona.
    die("Error crítico al obtener las familias: " . $e->getMessage());
}

// =======================================================================
// C. Obtener los datos actuales del producto (PARA RELLENAR EL FORMULARIO)
// =======================================================================
$producto_actual = null;

try {
    // Consulta para obtener todos los campos del producto por su ID
    $sql = "SELECT * FROM productos WHERE id = ?"; 
    
    // Consulta Preparada
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(1, $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Obtenemos una sola fila
    $producto_actual = $stmt->fetch();

    // Si fetch() devuelve 'false', el ID no existe, redirigimos.
    if (!$producto_actual) {
        header('Location: listado.php');
        exit(); 
    }

} catch (PDOException $e) {
    die("Error al obtener los datos del producto: " . $e->getMessage());
}
// En este punto, $producto_actual contiene los datos originales del producto

// ... Continuación del bloque PHP anterior ...

// =======================================================================
// D. Lógica de procesamiento del formulario (UPDATE)
// =======================================================================

// Comprobamos si el formulario fue enviado (si se pulsó el botón 'actualizar')
if (isset($_POST['actualizar'])) {

    // 1. Recogemos todos los datos del formulario (usamos el ID original, no el del POST)
    $nombre = trim($_POST["nombre"]);
    $nombrecorto = trim($_POST["nombrecorto"]);
    $precio = (float)$_POST["precio"]; // Convertimos a flotante
    $familia = $_POST["familia"];
    $descripcion = trim($_POST["descripcion"]);


    // 2. Validación de los campos obligatorios
    if (empty($nombre) || empty($nombrecorto) || empty($familia)) {

        $mensaje_error = "Debe rellenar todos los campos obligatorios.";
        
    } else if ($precio <= 0) {
        
        $mensaje_error = "El precio debe ser un valor positivo.";
        
    } else {

        // 3. Ejecución de la consulta de Actualización
        try {
            // Sentencia SQL: Usamos marcadores de posición nombrados y el ID para la condición WHERE
            $consultaActualizacion = "UPDATE productos SET 
                nombre = :nom, 
                nombre_corto = :nom_c, 
                pvp = :pvp, 
                descripcion = :descu, 
                familia = :fam 
                WHERE id = :id_prod";

            // Preparamos la sentencia
            $stmt = $conexion->prepare($consultaActualizacion);

            // Vinculamos los parámetros a los datos recogidos del POST
            $stmt->bindParam(":nom", $nombre);
            $stmt->bindParam(":nom_c", $nombrecorto);
            $stmt->bindParam(":pvp", $precio);
            $stmt->bindParam(":descu", $descripcion);
            $stmt->bindParam(":fam", $familia);
            
            // MUY IMPORTANTE: Vinculamos el ID del producto a actualizar
            $stmt->bindParam(":id_prod", $producto_id, PDO::PARAM_INT); 

            // Ejecutamos la consulta
            $stmt->execute();
            
            // 4. Redirección en caso de éxito
            // Redirigimos al listado después de actualizar (o al detalle)
            header('Location: listado.php'); 
            exit();

        } catch (PDOException $e) {
            $mensaje_error = "Error al actualizar el producto: " . $e->getMessage();
        }
    }
}
// Fin del bloque PHP
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto: <?php echo $producto_actual['nombre']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        
        <h1 class="text-center mb-4">Actualizar Producto #<?php echo $producto_id; ?></h1>

        <?php if ($mensaje_error): ?>
            <div class="alert alert-danger" role="alert"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>

        <form method="POST" action="update.php?id=<?php echo $producto_id; ?>" class="p-4 border rounded shadow-sm">
            
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($producto_actual['nombre']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="nombrecorto" class="form-label">Nombre Corto</label>
                <input type="text" class="form-control" id="nombrecorto" name="nombrecorto" 
                       value="<?php echo htmlspecialchars($producto_actual['nombre_corto']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="precio" class="form-label">PVP (€)</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                       value="<?php echo $producto_actual['pvp']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="familia" class="form-label">Familia</label>
                <select class="form-select" id="familia" name="familia" required>
                    <?php foreach ($familias as $familia_item): ?>
                        <option value="<?php echo $familia_item['cod']; ?>"
                            <?php 
                            // Comprueba si el código de familia de este item es igual al 
                            // código de familia del producto que estamos editando
                            if ($familia_item['cod'] === $producto_actual['familia']) {
                                echo 'selected'; 
                            }
                            ?>
                        >
                            <?php echo $familia_item['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="5"
                ><?php echo htmlspecialchars($producto_actual['descripcion']); ?></textarea>
            </div>
            
            <button type="submit" name="actualizar" class="btn btn-warning">Actualizar</button>
            <a href="listado.php" class="btn btn-secondary">Volver al Listado</a>

        </form>
    </div>
</body>
</html>