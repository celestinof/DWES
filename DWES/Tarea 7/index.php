<?php
session_start(); 
// Primero cargamos el autoload y la configuración de Jaxon
// Uso require_once para no cargar las clases dos veces por error
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/acciones.php';

// Llamo a la función global jaxon() que configuramos en acciones.php
$jaxon = jaxon();
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
    <title>Login Jaxon</title>
    <?php echo $jaxon->getJs(); ?>       <!-- carga librería JS de Jaxon DE PRIMERO!!!-->
    <?php echo $jaxon->getScript(); ?>   <!-- registra clases y funciones -->
    <script src="validar.js"></script>
    </head>

<body style="background:#00bfa5;">

    <div class="container mt-5">
        <div class="d-flex justify-content-center h-100">
            <div class="card" style='width:24rem;'>
                <div class="card-header">
                    <h3><i class="fa fa-cog mr-1"></i>Acceso</h3>
                </div>
                <div class="card-body">
                 <form name='miForm' id="miForm">
                        <div class="input-group form-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="usuario" id='usu' name='usu'>
                    </div>
                    <div class="input-group form-group">
                       <div class="input-group-prepend">
                           <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="contraseña" id='pass' name='pass'>
                    </div>
                   <div class="form-group">
                        <input type="button" value="Entrar" class="btn float-right btn-info" name='enviar' id="enviar" onclick="envForm();">
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>