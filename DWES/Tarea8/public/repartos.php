<?php
/**
 * Panel principal de Gestión de Repartos.
 * Coordina la API de Google Tasks para el almacenamiento y la librería Jaxon para la lógica asíncrona.
 */

include '../src/Tasks.php';

// --- CONFIGURACIÓN DE JAXON (En vez de AJAX ---
require_once '../vendor/autoload.php';
use Jaxon\Jaxon;

$jaxon = jaxon();
// Definimos el procesador de peticiones para mantener este archivo solo como vista
$jaxon->setOption('core.request.uri', '../src/Tools.php');
// Registramos la función de ordenación optimizada
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios');
// ---------------------------------------------------------------

// Instanciamos el servicio de Google Tasks con el cliente autenticado
$service = new Google_Service_Tasks($client);

/**
 * Obtiene todas las listas de tareas disponibles en la cuenta de Google.
 */
function getListasTareas()
{
    global $service;
    $optParams = ['maxResults' => 100];
    $results = $service->tasklists->listTasklists($optParams);
    return $results;
}

/**
 * Obtiene las tareas (pedidos) de una lista específica.
 */
function getTareas($id)
{
    global $service;
    $res1 = $service->tasks->listTasks($id);
    return $res1;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <title>Gestión de Repartos - Tarea 8 DWES</title>
    
    <?php 
        // Inserción de los motores JS de Jaxon
        echo $jaxon->getJs(); 
        echo $jaxon->getScript(); 
    ?>
    <script src="../js/funciones.js"></script>
</head>

<body style="background:#00bfa5;">
<?php
/**
 * LÓGICA DE CONTROLADOR (POST): Inserción de nuevas tareas.
 * Recibimos las coordenadas desde envio.php tras la validación de Jaxon.
 */
if (isset($_POST['lat'])) {
    $note = $_POST['lat'] . "," . $_POST['lon']; // Formateamos coordenadas para Google Maps
    $title = ucwords($_POST['pro']) . ". " . ucwords($_POST['dir']) . ", Almería. ";
    $idLt = $_POST['idLTarea'];
    
    // Limpiamos la sesión de ordenación previa para forzar recálculo
    unset($_SESSION[$idLt]);

    $op = ['title' => $title, 'notes' => $note];
    $tarea = new Google_Service_Tasks_Task($op);
    try {
        $service->tasks->insert($idLt, $tarea);
        header("Location: repartos.php"); // Redirección para evitar reenvío de formulario (PRG pattern)
        exit;
    } catch (Google_Exception $ex) {
        die("Error al guardar la tarea: " . $ex);
    }
}

/**
 * LÓGICA DE CONTROLADOR (GET): Acciones de borrado y gestión de estados.
 */
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'blt': // Borrar Lista completa
            try {
                $service->tasklists->delete($_GET['idlt']);
                unset($_SESSION[$_GET['idlt']]);
            } catch (Google_Exception $ex) { die("Error: " . $ex); }
            header("Location: repartos.php");
            exit;
        
        case 'bt': // Borrar Tarea individual
            try {
                $service->tasks->delete($_GET['idlt'], $_GET['idt']);
                unset($_SESSION[$_GET['idlt']]);
            } catch (Google_Exception $ex) { die("Error: " . $ex); }
            header("Location: repartos.php");
            exit;

        case 'ocultar': // Limpiar el orden optimizado de la vista
            unset($_SESSION[$_GET['idlt']]);
            header("Location: repartos.php");
            exit;

        case 'nlt': // Crear nueva Lista de Reparto
            $fecha_input = $_GET['fecha'];
            // Validación de fecha: no se permiten repartos en el pasado
            if (strtotime($fecha_input) < strtotime(date("Y-m-d"))) {
                echo "<script>alert('Fecha no válida'); window.location.href='repartos.php';</script>";
                exit;
            }

            $nombre_lista = "Repartos " . date("d/m/Y", strtotime($fecha_input));
            // Verificamos duplicados antes de insertar
            $listas_actuales = getListasTareas();
            foreach ($listas_actuales->getItems() as $lista) {
                if ($lista->getTitle() == $nombre_lista) {
                    echo "<script>alert('Ya existe un reparto para ese día'); window.location.href='repartos.php';</script>";
                    exit;
                }
            }

            $taskList = new Google_Service_Tasks_TaskList(["title" => $nombre_lista]);
            $service->tasklists->insert($taskList);
            header("Location: repartos.php");
            exit;

        case 'oEnvios': // Recibimos el orden optimizado calculado por Jaxon
            $apos = $_GET['pos'];
            $id_lista = $_GET['idLt'];
            $tareas = getTareas($id_lista);
            $arrayO = [];
            foreach ($apos as $k => $v) {
                $p = $v - 1; // Ajuste de índice
                $arrayO[$k] = $tareas->getItems()[$p]->getTitle();
            }
            $_SESSION[$id_lista] = $arrayO; // Guardamos en sesión para persistir la vista del orden
            header("Location: repartos.php");
            exit;
    }
}
?>

<h4 class="text-center mt-3 text-white">Gestión de Pedidos</h4>
<div class="container mt-4" style='width:80rem;'>
    <form action='repartos.php' method='get' class="bg-light p-3 rounded mb-4 shadow-sm">
        <div class="row">
            <div class="col-md-3">
                <button type='submit' class="btn btn-info btn-block"><i class='fas fa-plus mr-1'></i>Nueva Lista</button>
            </div>
            <input type='hidden' name='action' value='nlt'>
            <div class="col-md-4">
                <input type="date" class="form-control" name="fecha" required>
            </div>
        </div>
    </form>

    <?php
    $listas = getListasTareas();
    foreach ($listas->getItems() as $lista) {
        
        // FILTRO DE SEGURIDAD: Solo mostramos listas creadas por nuestra app para evitar ensuciar la UI
        if (strpos($lista->getTitle(), 'Repartos') !== 0) continue; 
        
        echo "<div class='card mb-4 shadow-sm'>";
        echo "<div class='card-header bg-secondary text-white d-flex justify-content-between align-items-center'>";
        echo "<span><i class='fas fa-clipboard-list mr-2'></i>{$lista->getTitle()}</span>";
        echo "<div>";
        
        // CONTROL DE INTERFAZ: Botón dinámico para plegar/desplegar la lista mediante jQuery
        echo "<button class='btn btn-warning btn-sm mr-2' onclick=\"$('#contenido-{$lista->getId()}').slideToggle();\"><i class='fas fa-eye-slash'></i> Ocultar </button>";
        
        echo "<a href='envio.php?id={$lista->getId()}' class='btn btn-info btn-sm mr-2'><i class='fas fa-plus'></i> Nuevo</a>";
        echo "<button class='btn btn-success btn-sm mr-2' onclick=\"ordenarEnvios('{$lista->getId()}')\"><i class='fas fa-sort'></i> Ordenar</button>";
        echo "<a href='repartos.php?action=blt&idlt={$lista->getId()}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Borrar Lista?')\"><i class='fas fa-trash'></i></a>";
        echo "</div></div>";
        
        // Contenedor colapsable
        echo "<div id='contenido-{$lista->getId()}'>";
        echo "<div class='card-body p-0'><table class='table table-hover mb-0'>";
        
        /**
         * ESTRUCTURA DE DATOS PARA JAXON:
         * Asignamos el ID de la lista al tbody para que el selector de jQuery en funciones.js
         * pueda localizar los puntos de esta lista específica.
         */
        echo "<tbody id='{$lista->getId()}' style='font-size:0.85rem'>";
        
        $tareas = getTareas($lista->getId());
        if($tareas->getItems()){
            foreach ($tareas->getItems() as $tarea) {
                echo "<tr>";
                // Campo oculto con coordenadas para que la función ordenarEnvios() las capture
                echo "<input type='hidden' value='{$tarea->getNotes()}'>"; 
                
                echo "<td class='align-middle'><strong>{$tarea->getTitle()}</strong> <br><small class='text-muted'>({$tarea->getNotes()})</small></td>";
                echo "<td class='text-right align-middle'>";
                echo "<a href='repartos.php?action=bt&idlt={$lista->getId()}&idt={$tarea->getId()}' class='btn btn-outline-danger btn-sm mr-2'><i class='fas fa-trash'></i></a>";
                // Enlace directo a mapa individual
                echo "<a href='mapa.php?coords=" . urlencode($tarea->getNotes()) . "' target='_blank' class='btn btn-outline-info btn-sm'><i class='fas fa-map'></i></a>";
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2' class='text-center text-muted p-3'>Lista vacía</td></tr>";
        }
        echo "</tbody></table></div>";

        /**
         * SECCIÓN DE ORDEN OPTIMIZADO:
         * Si la sesión contiene datos para esta lista, mostramos el resultado del algoritmo.
         */
        if (isset($_SESSION[$lista->getId()])) {
            echo "<div class='card-footer bg-light'>";
            echo "<div class='alert alert-info border-info mb-0'>";
            echo "<h6><i class='fas fa-route mr-2'></i>Ruta Optimizada</h6><hr>";
            echo "<ul class='list-unstyled mb-3'>";
            
            // Formulario para generar la hoja de ruta en mapa real (rutas.php)
            echo "<form action='rutas.php' method='POST' target='_blank'>";
            foreach ($_SESSION[$lista->getId()] as $k => $v) {
                echo "<li class='mb-1'><span class='badge badge-primary mr-2'>" . ($k + 1) . "</span> " . $v . "</li>";
                // Re-mapeamos títulos a coordenadas para el mapa final
                foreach ($tareas->getItems() as $t) {
                    if ($t->getTitle() == $v) {
                        echo "<input type='hidden' name='pos[]' value='{$t->getNotes()}'>";
                    }
                }
            }
            echo "</ul>";
            echo "<div class='text-center border-top pt-3'>";
            echo "<button type='submit' class='btn btn-success btn-sm mr-2'><i class='fas fa-map-marked-alt mr-1'></i>Hoja de Ruta</button>";
            echo "<a href='repartos.php?action=ocultar&idlt={$lista->getId()}' class='btn btn-warning btn-sm'>Ocultar</a>";
            echo "</div></form></div></div>";
        }
        echo "</div></div>"; // Cierre de contenido-id y card
    }
    ?>
</div>
</body>
</html>