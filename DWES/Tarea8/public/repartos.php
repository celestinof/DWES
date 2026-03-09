<?php
include '../src/Tasks.php';

// --- MAGIA JAXON ---
require_once '../vendor/autoload.php';
use Jaxon\Jaxon;

$jaxon = jaxon();
$jaxon->setOption('core.request.uri', '../src/Tools.php');
$jaxon->register(Jaxon::CALLABLE_FUNCTION, 'ordenarEnvios');
// -------------------

$service = new Google_Service_Tasks($client);

function getListasTareas()
{
    global $service;
    $optParams = ['maxResults' => 100];
    $results = $service->tasklists->listTasklists($optParams);
    return $results;
}

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
    <title>Repartos</title>
    
    <?php 
        echo $jaxon->getJs(); 
        echo $jaxon->getScript(); 
    ?>
    <script src="../js/funciones.js"></script>
</head>

<body style="background:#00bfa5;">
<?php
if (isset($_POST['lat'])) {
    $note = $_POST['lat'] . "," . $_POST['lon'];
    $title = ucwords($_POST['pro']) . ". " . ucwords($_POST['dir']) . ", Almería. ";
    $idLt = $_POST['idLTarea'];
    unset($_SESSION[$idLt]);
    //guardamos la tarea
    $op = ['title' => $title, 'notes' => $note];
    $tarea = new Google_Service_Tasks_Task($op);
    try {
        $res = $service->tasks->insert($idLt, $tarea);
        header("Location: repartos.php"); // Evitamos reenvío de formulario
        exit;
    } catch (Google_Exception $ex) {
        die("Error al guardar la tarea: " . $ex);
    }
    unset($_POST['lat']);
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'blt':
            try {
                $service->tasklists->delete($_GET['idlt']);
                unset($_SESSION[$_GET['idlt']]);
            } catch (Google_Exception $ex) {
                die("Error al borrar la lista de tareas: " . $ex);
            }
            header("Location: repartos.php");
            exit;
        
        case 'bt':
            try {
                $service->tasks->delete($_GET['idlt'], $_GET['idt']);
                unset($_SESSION[$_GET['idlt']]);
            } catch (Google_Exception $ex) {
                die("Error al borrar la tarea: " . $ex);
            }
            header("Location: repartos.php");
            exit;

        case 'ocultar':
            unset($_SESSION[$_GET['idlt']]);
            header("Location: repartos.php");
            exit;

        case 'nlt':
            $fecha_input = $_GET['fecha'];
            $timestamp_input = strtotime($fecha_input);
            $timestamp_hoy = strtotime(date("Y-m-d"));
            
            if ($timestamp_input < $timestamp_hoy) {
                echo "<script>alert('La fecha no puede ser inferior a la actual'); window.location.href='repartos.php';</script>";
                exit;
            }

            $fecha_formateada = date("d/m/Y", $timestamp_input);
            $nombre_lista = "Repartos " . $fecha_formateada;

            $listas_actuales = getListasTareas();
            $existe = false;
            foreach ($listas_actuales->getItems() as $lista) {
                if ($lista->getTitle() == $nombre_lista) {
                    $existe = true;
                    break;
                }
            }

            if ($existe) {
                echo "<script>alert('Ya existe un reparto para ese dia !!!'); window.location.href='repartos.php';</script>";
                exit;
            }

            $opciones = ["title" => $nombre_lista];
            $taskList = new Google_Service_Tasks_TaskList($opciones);
            try {
                $service->tasklists->insert($taskList);
                header("Location: repartos.php");
                exit;
            } catch (Google_Exception $ex) {
                die("Error al crear una lista de tareas: " . $ex);
            }
            break;

        case 'oEnvios':
            $apos = $_GET['pos'];
            $id_lista = $_GET['idLt'];
            $tareas = getTareas($id_lista);
            $arrayO = [];
            foreach ($apos as $k => $v) {
                $p = $v - 1;
                $arrayO[$k] = $tareas->getItems()[$p]->getTitle();
            }
            $_SESSION[$id_lista] = $arrayO;
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
                <button type='submit' class="btn btn-info btn-block"><i class='fas fa-plus mr-1'></i>Nueva Lista de Reparto
                </button>
            </div>
            <input type='hidden' name='action' value='nlt'>
            <div class="col-md-4">
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
        </div>
    </form>

    <?php
    $listas = getListasTareas();
    foreach ($listas->getItems() as $lista) {
        
        // Si el título NO empieza por "Repartos", saltamos a la siguiente lista y la ignoramos
        if (strpos($lista->getTitle(), 'Repartos') !== 0) {
            continue; 
        }
        
        echo "<div class='card mb-4 shadow-sm'>";
        echo "<div class='card-header bg-secondary text-white d-flex justify-content-between align-items-center'>";
        echo "<span><i class='fas fa-clipboard-list mr-2'></i>{$lista->getTitle()}</span>";
        echo "<div>";
        
        //  Botón de Ocultar/Desplegar con animación
        echo "<button class='btn btn-warning btn-sm mr-2' onclick=\"$('#contenido-{$lista->getId()}').slideToggle();\"><i class='fas fa-eye-slash'></i> Ocultar </button>";
        
        echo "<a href='envio.php?id={$lista->getId()}' class='btn btn-info btn-sm mr-2'><i class='fas fa-plus'></i> Nuevo</a>";
        echo "<button class='btn btn-success btn-sm mr-2' onclick=\"ordenarEnvios('{$lista->getId()}')\"><i class='fas fa-sort'></i> Ordenar</button>";
        echo "<a href='repartos.php?action=blt&idlt={$lista->getId()}' class='btn btn-danger btn-sm' onclick=\"return confirm('¿Borrar Lista?')\"><i class='fas fa-trash'></i> Borrar</a>";
        echo "</div></div>";
        
        // Envolvemos" el cuerpo en un div con ID único para poder ocultarlo
        echo "<div id='contenido-{$lista->getId()}'>";
        
        echo "<div class='card-body p-0'><table class='table table-hover mb-0'>";
    
        //  AQUÍ ESTÁ LA MAGIA: Le ponemos el ID de la lista al tbody
        echo "<tbody id='{$lista->getId()}' style='font-size:0.85rem'>";
        
        $tareas = getTareas($lista->getId());
        if($tareas->getItems()){
            foreach ($tareas->getItems() as $tarea) {
                echo "<tr>";
                
                // 🚨 AQUÍ ESTÁ LA MAGIA 2: Input oculto con las coordenadas
                echo "<input type='hidden' value='{$tarea->getNotes()}'>"; 
                
                echo "<td class='align-middle'><strong>{$tarea->getTitle()}</strong> <br><small class='text-muted'>Coordenadas: ({$tarea->getNotes()})</small></td>";
                echo "<td class='text-right align-middle'>";
                echo "<a href='repartos.php?action=bt&idlt={$lista->getId()}&idt={$tarea->getId()}' class='btn btn-outline-danger btn-sm mr-2' onclick=\"return confirm('¿Borrar Tarea?')\"><i class='fas fa-trash'></i></a>";
                echo "<a href='mapa.php?coords=" . urlencode($tarea->getNotes()) . "&dir=" . urlencode($tarea->getTitle()) . "' target='_blank' class='btn btn-outline-info btn-sm'><i class='fas fa-map'></i> Mapa</a>";
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2' class='text-center text-muted p-3'>No hay tareas pendientes</td></tr>";
        }
        echo "</tbody></table></div>";

        if (isset($_SESSION[$lista->getId()])) {
            echo "<div class='card-footer bg-light'>";
            echo "<div class='alert alert-info border-info mb-0'>";
            echo "<h6><i class='fas fa-route mr-2'></i>Orden de Reparto Optimizado</h6><hr>";
            echo "<ul class='list-unstyled mb-3'>";
            
            echo "<form action='rutas.php' method='POST' target='_blank'>";
            foreach ($_SESSION[$lista->getId()] as $k => $v) {
                echo "<li class='mb-1'><span class='badge badge-primary mr-2'>" . ($k + 1) . "</span> " . $v . "</li>";
                foreach ($tareas->getItems() as $t) {
                    if ($t->getTitle() == $v) {
                        echo "<input type='hidden' name='pos[]' value='{$t->getNotes()}'>";
                    }
                }
            }
            echo "</ul>";
            echo "<div class='text-center border-top pt-3'>";
            echo "<button type='submit' class='btn btn-success btn-sm mr-2'><i class='fas fa-map-marked-alt mr-1'></i>Ver Ruta en Mapa</button>";
            echo "<a href='repartos.php?action=ocultar&idlt={$lista->getId()}' class='btn btn-warning btn-sm'><i class='fas fa-eye-slash mr-1'></i>Ocultar Orden</a>";
            echo "</div></form></div></div>";
        }
        echo "</div>";
    }
    ?>
</div>
</body>
</html>