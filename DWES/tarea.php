<?php

/**Con la funcion "session_start()" preparamos nuestra página para poder llamar a la variable superglobal $_SESSION
 *  y mantener la persistencia de datos durante la sesion (en un archivo temporal que se pierde al cerrar el navegador)*/
session_start(); 

    //VARIABLES: 
     

    //1. INICIO
    //La agenda persistente en $_SESSION. Utilizamos la etiqueta "agenda" para acceder a los elementos de agenda en el array $_SESSION
    //Usamos un if para verificar si la agenda existe. Si no existe, se crea, si existe, no se vuelve a crear (Si no, borra toda la agenda y comienza desde el principio)
    if (!isset($_SESSION["agenda"])) {
        $_SESSION["agenda"] = [];}
    
    //2. CARGAR LA AGENDA EN UNA VARIABLE PARA MEJOR MANEJO Y DECLARAR VARIABLES.
    /** Inicializamos las variables a emplear. Aunque no es necesario creo que es una buena práctica y da mejor legibilidad al código.
    * - Array agenda. Es la agenda "temporal" que posteriormente guardaremos en la persistente
    * - Al inicio de cada proceso, se carga con la agenda existente en la persistente.*/
    $agenda=$_SESSION["agenda"];
    //Variables nombre y variable teléfono, para guardar los datos.
    $nombre="";
    $telefono=0;


    //COMIENZA LA LÓGICA

    //3. SE INICIA AL COMPROBAR QUE SE HA PULSADO ENVIAR. 
    /**Comprobamos si la clave 'enviar' existe en el array superglobal $_POST.
    * $_POST contiene todos los datos enviados por un formulario con metodo POST.*/
    if(isset($_POST["enviar"])) {
        
        //El valor introducido en la casilla "nombre y apellidos" se almacena en la variable $nombre
        $nombre=$_POST["nombre"];
        //El valor introducido en la casilla "nombre y apellidos" se almacena en la variable $telefono 
        $telefono=$_POST["telefono"];  
       
    //EVALUAR LOS DATOS QUE SE PASAN POR POST.
    //3.1. EVALUAR SI EL NOMBRE ESTÁ VACÍO 
    /**En el video proporcionado en los apuntes (video 8.2 de aulaclic), se muestra que una forma de validar es con un condicional
     * que comprueba "if ($_REQUEST["nombre"]="")". No obstante tal y como se explica en los apuntes del curso, creo que es mejor utilizar
     * empty() para verificar además la existencia de valores nulos*/
    
    
    if (empty($nombre)) {
    //3.1.1. EL NOMBRE ESTÁ VACÍO. TERMINA LA EJECUCIÓN.
        echo "El campo nombre está vacío";
    }

    //3.1.2. EL NOMBRE NO ESTÁ VACÍO
    /**Comprobación de si el nombre ya está en la agenda y si el teléfono ya estaba grabado.
     * 3.1.2.1. Si el nombre no está en la agenda, graba la clave nombre y el valor teléfono en array agenda
     * 3.1.2.2. Si el nombre ya está en la agenda y se escribió teléfono, sobreescribe el teléfono.
     * 3.1.2.3. Si el nombre ya está en la agenda y no se escribió teléfono, se elimina el registro de nombre. */ 

    //Evaluamos si el nombre no existe en la agenda (por eso uso un isset inverso). 
        else { 
                 if (!isset($agenda[$nombre]) && !empty($telefono)) {
    //Si el nombre no existe en la agenda, 3.1.2.1.
                    $agenda[$nombre]=$telefono;    
    } 

    //En caso de que el nombre exista, vamos a evaluar que pasa con teléfono. Pasamos a ejecutar este bloque para ver si acabamos en 3.1.2.2 o 3.1.2.3
    else {
            if (empty($telefono))
            //Si el teléfono está vacío, 3.1.2.3.
                {unset($agenda[$nombre]);} 
             
             //Como el teléfono también existe, acabamos en 3.1.2.2.
             else {
                $agenda[$nombre]=$telefono;}          

}}} //Aquí termina el bloque POST


 // Lógica de Vaciar (GET), si se pulsa el botón para vaciar la agend. En la primera página no estará disponible (Hasta que haya grabados datos). 
    // Saldrá una vez haya datos en la agenda.
    /**Utilizo else if para que no haya conflicto con el bloque POST anterior. 
     * Cuando utilizaba dos bloques if independientes, al pulsar el botón de vaciar agenda (GET), el navegador se queda clavado en la URL con el parámetro GET
     * y si a continuación se pulsa el botón de enviar (POST), el navegador interpreta que se quieren ejecutar ambos bloques (GET y POST) y por algún motivo que no entiendo
     * no funciona correctamente. Al usar else if, al pulsar el botón de vaciar agenda (GET), solo se ejecuta ese bloque cuando no se ha llamado al POST.*/
    else if (isset($_GET["borrar_agenda"])) {
    $agenda = [];}

    //ACTUALIZAMOS LA AGENDA CON EL RESULTADO QUE SEA (ya se haya pulsado el POST o el GET)
    $_SESSION["agenda"] = $agenda;    

?>



<!-- ---------------HTML ---------------------------->
<!-- Iniciamos el archivo HTML, como cualquier otro (Dejo lo que VSCODE da por defecto)-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tarea.css">  
    <title>Tareaud2</title>
</head>
<body>
    <header>
    <h1>Agenda</h1>
    </header>
   
   <main>

    <!-- Primer inserción en el cuerpo HTML. Solo debe de mostrarse si hay resultados
     guardados en la agenda. Usaremos "templating". -->
    <!--MODO PHP-->
    <?php
        if (!empty($agenda)) {

    ?>
    <!--MODO HTML, que abrirá el div de la agenda solo si se cumple la condicion PHP-->   
            <div class="datos_agenda"> 
    <!--MODO PHP, para seguir aplicando la lógica y desde el que imprimimos-->      
           <?php
            echo "<h3> Agenda telefónica </h3>";
    
            foreach($agenda as $nombreagenda => $telefonoagenda){            
                echo "<p> Nombre: $nombreagenda  Teléfono: $telefonoagenda </p>";
            }
    ?>
    <!--MODO HTML, para cerrar el <div>-->  
        </div>
    <!--MODO PHP para poder cerrar el bracket que encierra el div en el if de PHP-->
    <?php } ?>
    

    

    
    <!-- EL formulario es una parte HTML normal, que siempre será visible-->
    <div class="formulario-contacto">
        <h2>Nuevo Contacto</h2>
        
        <!--Datos para la agenda. En el atributo action lo dejamos vacío, para que mande las respuestas a esta misma página-->
        <form action="" method="POST" id="valcontacto"> 
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" placeholder="Introducir nombre">
            </div>
            
            <div>
                <label for="telefono">Teléfono:</label>
                <input type="tel" name="telefono" id="telefono" maxlength="9" placeholder="Introducir teléfono">
            </div>

            <br>

        <!--Botones para envío y para reset-->
            <div>
                <input type="submit" name="enviar" value="Añadir Contacto">
                <input type="reset" value="Borrar">
            </div>
        </form>
    </div>


    <!--El último bloque, solo debe aparecer si la agenda tiene entradas-->
    <?php
    if (!empty($agenda)) {?>
    <form action="" method="GET" class="delagenda">
        <div>
            <input type="submit" name="borrar_agenda" value="Vaciar Agenda">
        </div>    
    <?php 
    } ?>
    

</main>