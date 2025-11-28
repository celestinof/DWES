<?php
// 1. Incluimos el archivo de conexión para poder usar $conexion.
// Como nos indica el enunciado, usamos require_once para asegurarnos
// de que el archivo se incluye una única vez.
require_once 'conexion.php';

//Creamos variables de control para errores y otros mensajes varios.
$mensaje_error=null;
$mensaje_exito=null;
$mensaje_generico=null;



// =======================================================================
// A. LÓGICA DE PROCESAMIENTO DEL FORMULARIO (CREATE)
// =======================================================================

// Comprobamos si el formulario fue enviado (si se pulsó el botón 'crear')
if (isset($_POST['crear'])) {

    $nombre=($_POST["nombre"]);
    $nombrecorto=($_POST["nombrecorto"]);
    $precio=($_POST["precio"]);
    $familia=($_POST["familia"]);
    $descripcion=($_POST["descripcion"]);


if(empty($nombre) || empty($nombrecorto) || empty($familia)){

    $mensaje_error="Debe rellenar todos los campos obligatorios.";
} else if($precio<=0){
    $mensaje_error="El precio no puede ser negativo";
} else{

        // 3. Inserción Segura en la Base de Datos con Consultas Preparadas
        try {
            // Utilizamos marcadores de posición inventados
            $consultaInsercion = "INSERT INTO productos (nombre, nombre_corto, pvp, descripcion, familia) 
                    VALUES (:nom, :nom_c, :pvp, :descu, :fam)";

            // Preparamos la sentencia
            $stmt = $conexion->prepare($$consultaInsercion);

            // Vinculamos los parámetros a los datos recogidos del POST
            $stmt->bindParam(":nom", $nombre);
            $stmt->bindParam(":nom_c", $nombreCorto);
            $stmt->bindParam(":pvp", $precio);
            $stmt->bindParam(":descu", $descripcion);
            $stmt->bindParam(":fam", $familia);

            // Ejecutamos la consulta
            $stmt->execute();
            $mensaje_exito="Producto creado con éxito.";
            exit();

        } catch (PDOException $e) {     
            $mensaje_error="Error al crear el producto: " . $e->getMessage();
        }
       

}

}

// =======================================================================
// B. Obtener las familias para el select
// =======================================================================
$familias = []; //

try {
    // Consulta para obtener el código y nombre de todas las familias
    $stmt = $conexion->query("SELECT cod, nombre FROM familias ORDER BY nombre");
    
    // Almacenamos todos los resultados en el array $familias
    $familias = $stmt->fetchAll();

} catch (PDOException $e) {
    // Si la consulta falla (ej. la tabla familias no existe)
    die("Error crítico al obtener las familias: " . $e->getMessage());
}

// Nota: Ahora sí cerramos el bloque PHP para continuar con el HTML
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        
        <h1 class="text-center mb-4">Crear Producto</h1>

        <form action="" method="Post" id="validproduct" class="row g-3 p-4 border rounded shadow-sm">
            
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre completo del producto" required>
            </div>
            
            <div class="col-md-6">
                <label for="nombrecorto" class="form-label">Nombre Corto</label>
                <input type="text" name="nombrecorto" id="nombrecorto" class="form-control" placeholder="Ej: ACEI5-4GB1TB" required>
            </div>
            
            <div class="col-md-6">
                <label for="precio" class="form-label">Precio (€)</label>
                <input type="number" name="precio" id="precio" class="form-control" placeholder="0.00" step="0.01" min="0" required>
            </div>

            <div class="col-md-6">
                <label for="familia" class="form-label">Familia</label>
                <select name="familia" id="familia" class="form-select" required>
                    <option value="">Selecciona una familia...</option>
                    </select>
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="5" placeholder="Características detalladas del producto..."></textarea>
            </div>
            
            <div class="col-12 mt-4">
                <input type="submit" value="Crear" name="crear" class="btn btn-success me-2"> 
                <input type="reset" value="Limpiar" name="limpiar" class="btn btn-warning me-2">
                <a href="listado.php" class="btn btn-secondary">Volver</a>
            </div>
            
        </form>
    </div>

</body>
</html>