<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/acciones.php';
$jaxon = jaxon();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <?php echo $jaxon->getJs() ?>
    <?php echo $jaxon->getScript() ?>
</head>
<body>
    <div style="width:300px; margin:auto; padding-top:50px;">
        <h2>Acceso</h2>
        <input type="text" id="u" placeholder="Usuario"><br><br>
        <input type="password" id="p" placeholder="Clave"><br><br>
        <button onclick="jaxon_Votacion.validarLogin(document.getElementById('u').value, document.getElementById('p').value)">
            Entrar
        </button>
    </div>
</body>
</html>