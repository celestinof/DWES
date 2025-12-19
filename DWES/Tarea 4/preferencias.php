<?php
// OBLIGATORIO: Iniciar la sesión al comienzo del script para poder usar $_SESSION
session_start();

// Variable para el mensaje de notificación de éxito
$mensaje_preferencias = '';

// --- LÓGICA DE GUARDADO ---
// 1. Verificar si se ha enviado el formulario (el botón 'establecer')
if (isset($_POST['establecer'])) {
    
    // 2. Guardar los valores recibidos de $_POST en el array de Sesión ($_SESSION)
    $_SESSION['idioma'] = $_POST['idioma'];
    $_SESSION['perfil'] = $_POST['perfil'];
    $_SESSION['zona_horaria'] = $_POST['zona_horaria'];
    
    // 3. Establecer el mensaje de éxito
    $mensaje_preferencias = 'Preferencia de usuario guardadas.';
}

// --- LÓGICA DE RECUPERACIÓN (Persistencia visual) ---
// Se crean variables que contienen el valor actual de la preferencia.
// Si la preferencia existe en la sesión, se usa ese valor; si no, se usa un valor por defecto.

//Idioma. El establecido en la preferencia. Si no, el español por defecto.
if ($idioma_actual=isset($_SESSION['idioma'])){
    $idioma_actual=$_SESSION["idioma"];} else {
    $idioma_actual='espanol';        
    }

//Perfil público. El establecido en la preferencia. Si no, 'si' por defecto.
if($perfil_actual=isset($_SESSION['perfil'])){
    $perfil_actual=$_SESSION["perfil"];} else {
    $perfil_actual='si';        
    }

//Zona horaria. El establecido en la preferencia. Si no, 'GMT' por defecto.
if($zona_horaria_actual
=isset($_SESSION['zona_horaria'])){
    $zona_horaria_actual=$_SESSION["zona_horaria"];} else {
    $zona_horaria_actual='GMT';        
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Preferencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4 text-primary"><i class="fas fa-cog"></i> Configuración de Preferencias</h1>

        <?php if ($mensaje_preferencias): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>¡Éxito!</strong> <?php echo $mensaje_preferencias; ?>
            </div>
        <?php endif; ?>

        <div class="card p-4 shadow-sm">
            
            <form method="POST" action="preferencias.php">
                
                <h4 class="card-title mb-3">Selecciona tus opciones:</h4>

                <div class="row mb-4">
                    <!--Usamos la palabra de HTML reservada selected en los desplegables de option
                    para lograr que lo que se ve en el navegador (lo seleccionado), sea lo que se envía
                    como dato persistente a $_SESSION y no lo que se carga con la web-->    

                    <div class="col-md-4">
                        <label for="idioma" class="form-label">Idioma</label>
                        <select class="form-select" id="idioma" name="idioma">
                            <option value="ingles" <?php if ($idioma_actual == 'ingles') echo 'selected'; ?>>Inglés</option>
                            <option value="espanol" <?php if ($idioma_actual == 'espanol') echo 'selected'; ?>>Español</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="perfil" class="form-label">Perfil público</label>
                        <select class="form-select" id="perfil" name="perfil">
                            <option value="si" <?php if ($perfil_actual == 'si') echo 'selected'; ?>>Sí</option>
                            <option value="no" <?php if ($perfil_actual == 'no') echo 'selected'; ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="zona_horaria" class="form-label">Zona horaria</label>
                        <select class="form-select" id="zona_horaria" name="zona_horaria">
                            <option value="GMT-2" <?php if ($zona_horaria_actual == 'GMT-2') echo 'selected'; ?>>GMT-2</option>
                            <option value="GMT-1" <?php if ($zona_horaria_actual == 'GMT-1') echo 'selected'; ?>>GMT-1</option>
                            <option value="GMT" <?php if ($zona_horaria_actual == 'GMT') echo 'selected'; ?>>GMT</option>
                            <option value="GMT+1" <?php if ($zona_horaria_actual == 'GMT+1') echo 'selected'; ?>>GMT+1</option>
                            <option value="GMT+2" <?php if ($zona_horaria_actual == 'GMT+2') echo 'selected'; ?>>GMT+2</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary" name="establecer">
                    <i class="fas fa-save"></i> Establecer preferencias
                </button>
                
                <a href="mostrar.php" class="btn btn-outline-secondary">
                    <i class="fas fa-eye"></i> Mostrar preferencias
                </a>

            </form>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>