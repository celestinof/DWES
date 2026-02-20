<?php
// Iniciamos sesión para saber quién es el usuario
session_start();

// Si el usuario no está logueado (porque ha intentado entrar escribiendo la URL), 
// lo mandamos al index.php (nuestro login) de cabeza.
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    die();
}

// Cargamos el autoload de Composer y nuestra configuración de Jaxon
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/acciones.php';

// Usamos la clase Producto para traernos todos los artículos de la base de datos
use App\Producto;
$productos = new Producto();
$todos = $productos->listadoProductos();

// Preparamos Jaxon
$jaxon = jaxon();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    
    <title>Tienda de Productos - Tarea 7</title>
    
    <?php echo $jaxon->getJs(); ?>
    <?php echo $jaxon->getScript(); ?>
    
    <script type="text/javascript" src="validar.js"></script>
    
    <script>
        // Nada más cargar la página, le pedimos a Jaxon que pinte las estrellas
        // Uso window.onload para asegurarme de que todo el HTML está listo
        window.onload = function() {
            jaxon_Votacion.pintarEstrellas();
        };
    </script>
</head>
<body style="background:gainsboro">

<div class="float-right d-inline-flex mt-2">
    <i class="fas fa-user mr-3 fa-2x"></i>
    <input type="text" size='10px' value="<?php echo $_SESSION['usuario']; ?>" 
           class="form-control mr-2 bg-transparent text-info font-weight-bold" disabled>
    <a href="salir.php" class="btn btn-warning mr-2">Salir</a>
</div>

<br>
<h4 class="container text-center mt-4 font-weight-bold">Productos Online</h4>

<div class="container mt-3">
    <table class="table table-striped table-dark">
      <thead>
        <tr class='text-center'>
          <th scope="col">Código</th>
          <th scope="col">Nombre</th>
          <th scope="col">Valoración</th>
          <th scope="col">Valorar</th>
        </tr>
      </thead>
      <tbody>
          <?php
            // Recorremos los productos uno a uno. Uso FETCH_OBJ porque es más cómodo que el array
            while($item = $todos->fetch(PDO::FETCH_OBJ)){
               // El ID de los votos es dinámico: votos_1, votos_2... para que Jaxon sepa dónde escribir
               echo "<tr class='text-center'>";
               echo "<th scope='row'>{$item->id}</th>";
               echo "<td>{$item->nombre}</td>";
               echo "<td><div id='votos_{$item->id}'>Cargando...</div></td>";
               echo "<td>";
               
               // Formulario para votar. Cada producto tiene su propio formulario con su ID
               echo "<form name='miForm_{$item->id}' id='miForm_{$item->id}'>";
               echo "<input name='idPr' type='hidden' value='{$item->id}'>";
               echo "<input name='idUs' type='hidden' value='{$_SESSION['usuario']}'>";
               echo "<div class='row'>";
               echo "  <div class='col'>";
               echo "    <select class='form-control' name='cantidad'>";
               echo "      <option value='1'>1</option><option value='2'>2</option>";
               echo "      <option value='3'>3</option><option value='4'>4</option>";
               echo "      <option value='5'>5</option>";
               echo "    </select>";
               echo "  </div>";
               echo "  <div class='col'>";
               // Al hacer clic, llamamos a envFormVoto que está en nuestro validar.js
               echo "    <input type='button' value='Votar' class='btn btn-info' 
                          onclick=\"envFormVoto(jaxon.getFormValues('miForm_{$item->id}'))\" />";
               echo "  </div>";
               echo "</div>";
               echo "</form>";
               
               echo "</td>";
               echo "</tr>";
            }
          ?>
      </tbody>
    </table>
</div>

</body>
</html>