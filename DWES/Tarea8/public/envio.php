<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

if (!isset($_GET['id'])) {
    header('Location:repartos.php');
    die();
}
$id = $_GET['id'];

// --- MAGIA JAXON ---
require_once '../vendor/autoload.php';
use Jaxon\Jaxon;

$jaxon = jaxon();
// Le decimos dónde vive el código PHP que tiene que ejecutar
$jaxon->setOption('core.request.uri', '../src/Tools.php');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'getCoordenadas');
// -------------------

try {
    $con = new PDO("mysql:host=localhost;dbname=proyecto;charset=utf8mb4", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $con->query("SELECT nombre_corto FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    die("Error en la conexión a la BD: " . $ex->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Apartado 4-3</title>
    
    <?php 
        echo $jaxon->getJs(); 
        echo $jaxon->getScript(); 
    ?>
    <script type="text/javascript" src="../js/funciones.js"></script>
</head>

<body style="background:#00bfa5;">
<div class="container mt-3">
    <div class="d-flex justify-content-center h-100">
        <div class="card" style='width:28rem;'>
            <div class="card-header">
                <h3><i class="fas fa-cart-plus mr-2"></i>Crear Envio</h3>
            </div>
            <div class="card-body">
                <form name="f1" method='POST' action='repartos.php'>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width:2.5rem;"><i class="fas fa-city"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Dirección" id='dir' name='dir' required>
                    </div>
                    
                    <div class="form-group mt-1">
                        <button type="button" class="btn btn-info mr-2" id="vDireccion" onclick="getCoordenadas()">
                            Ver Coordenadas
                        </button>              
                    </div>
                    
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Latitud" id='lat' required name='lat' readonly>
                    </div>
                    
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Longitud" id='lon' name='lon' required readonly>
                    </div>
                    
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info" style="width: 14px; text-align: center;"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Altitud" id='alt' name='alt' readonly>
                    </div>

                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                        </div>
                        <select class="form-control" id='pro' name="pro" required>
                            <option value="">Elige un producto</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?php echo htmlspecialchars($producto['nombre_corto']); ?>">
                                    <?php echo htmlspecialchars($producto['nombre_corto']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="idLTarea" value="<?php echo $id; ?>">
                        <input type='submit' class="btn btn-info mr-2" value="Nuevo Envio">
                        <a href="repartos.php" class="btn btn-success">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>