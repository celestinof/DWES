<?php
// TIENE QUE IR AL PRINCIPIO: Iniciar la sesión al comienzo para que funcione $_SESSION
session_start();

// Variable para el mensaje de notificación de éxito o error
$mensaje_mostrar = '';

// --- LÓGICA DE BORRADO ---
// 1. Verificar si se ha pulsado el botón 'borrar'
if (isset($_POST['borrar'])) {
    
    // Verificar si las preferencias están realmente establecidas antes de borrar
    if (isset($_SESSION['idioma'])) {
        
        /*
        Podríamos borrar las preferencias una a una pero es más
        eficiente usar session_unset y session_destroy.
        unset($_SESSION['idioma']);
        unset($_SESSION['perfil']);
        unset($_SESSION['zona_horaria']);*/
        
        //Primero vaciamos el array de sesión.
        session_unset(); 
        
        // Aunque no es necesario, destruimos la sesión para asegurar el borrado completo.
        session_destroy();
        
        $mensaje_mostrar = 'Preferencias Borradas.';
    } else {
        //Si no hay preferencias establecidas, no hay nada que borrar.
        $mensaje_mostrar = 'Debes fijar primero las preferencias.';
    }
}


// Mostrar o no las preferencias (si existen en la sesión)
// Se comprueba si al menos una clave de preferencia existe en la sesión. Con que haya una, quiere decir que se guardaron en algún momento,
//aunque sean las preferencias por defecto.
$hay_preferencias = isset($_SESSION['idioma']); 

?>
<!DOCTYPE html>
<html lang="es">
<head> <!-- Cabecera HTML, como siemprem indicando los links de bootstrap -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Preferencias</title>
    <!-- Bootsrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4 text-success"><i class="fas fa-eye"></i> Preferencias Guardadas</h1>

        <?php 
    // Comprobamos si hay algún mensaje para mostrar
    if ($mensaje_mostrar) { 
        ?>
        <div class="alert <?php echo $clase_alerta; ?> alert-dismissible fade show" role="alert">
        <strong>Aviso:</strong> <?php echo $mensaje_mostrar; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
        // Cerramos el bloque if
            } 
    ?>

        <div class="card p-4 shadow-sm">
            
            <?php if ($hay_preferencias): ?>
                <h4 class="card-title mb-3">Tus preferencias actuales:</h4>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><i class="fas fa-language me-2"></i> Idioma: <strong><?php echo $_SESSION['idioma']; ?></strong></li>
                    <li class="list-group-item"><i class="fas fa-user-circle me-2"></i> Perfil público: <strong><?php echo $_SESSION['perfil']; ?></strong></li>
                    <li class="list-group-item"><i class="fas fa-clock me-2"></i> Zona horaria: <strong><?php echo $_SESSION['zona_horaria']; ?></strong></li>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No hay preferencias de usuario guardadas en la sesión.
                </div>
            <?php endif; ?>

            <hr>

            <div class="d-flex justify-content-between">
                
                <form method="POST" action="mostrar.php" class="d-inline">
                    <button type="submit" class="btn btn-danger" name="borrar">
                        <i class="fas fa-trash"></i> Borrar
                    </button>
                </form>
                
                <a href="preferencias.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Establecer
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>