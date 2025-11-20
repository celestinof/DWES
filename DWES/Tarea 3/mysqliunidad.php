<!--PUNTO 3.1.1-->
<?php

//Declaramos variables para la conexion
$servidor="localhost";
$usuario="gestor"; 
$contrasena="secreto";
$bbdd="proyecto"; 

//A------ Conexión a la base de datos 'proyecto' usando la clase mysqli (POO) ------

        //1. Conexion. Forma más simple: $conexionProyecto=new mysqli("localhost","gestor","secreto","proyecto");

        //2. Conexión paso a paso:
        $conexionProyecto=new mysqli();
        //llamamos al método connect de la clase mysqli
        $conexionProyecto->connect($servidor,$usuario,$contrasena,$bbdd);


//B----- Comprobar la conexion------------
/*
connect_errno (o la función mysqli_connect_errno) devuelve el número de error o null si no se produce ningún error.
connect_error (o la función mysqli_connect_error) devuelve el mensaje de error o null si no se produce ningún error. */

        //podríamos hacerlo solo con una de las dos variables, pero usaremos las dos para practicar
        $errno=$conexionProyecto->connect_errno;
        $error=$conexionProyecto->connect_error;

        //Si error es null, no hay error. En caso contrario, manejamos el error.
        if (errno!=$null){
            echo"<p>Error de conexión: $error.</p>";

            //Finalizamos la ejecución del script
            die();
        }

        //cambiar a otra bbdd, por ejemplo proyecto2
        $conexionProyecto->select_db("proyecto2");

        //para volver a proyecto (recordar que no cambia el nombre de la variable, aunque ya no sea descriptivo)
        $conexionProyecto->select_db("proyecto");


        //cerrar bbdd
        $conexionProyecto->close();

      



//A------ Conexión a la base de datos 'proyecto' usando forma procedural ------

/*$conProyecto = mysqli_connect('localhost', 'gestor', 'secreto', 'proyecto');
if (!$conProyecto) {
    die("Error de conexión (procedural): " . mysqli_connect_error());
}*/


?>

                                <!--PUNTO 3.1.2 CONSULTAS-->

        
        <?php
        //conectamos a la bbdd proyecto (directamente en una línea, con los argumentos)
        @$conProyecto = new mysqli('localhost', 'gestor', 'secreto', 'proyecto');
        //guardamos el error para verificar la conexión
        $error = $conProyecto->connect_errno;
        //si no hay error, seguimos
        if ($error == null) {
            //realizamos la consulta de borrado. Las consultas de accion (INSERT, UPDATE, DELETE) devuelven true o false
            $resultado = $conProyecto->query('DELETE FROM stock WHERE unidades=0');
            //si la consulta se ha realizado correctamente, mostramos el número de registros borrados
            if ($resultado) {
                //usamos la propiedad affected_rows para ver el número de filas afectadas por la última consulta
                echo "<p>Se han borrado $conProyecto->affected_rows registros.</p>";
            }
            $conProyecto->close(); //cerramos la conexion
        }

        //En el caso de ejecutar una sentencia SQL que sí devuelva datos (como un SELECT), éstos se devuelven en forma de un objeto resultado
        //El argumento MYSQLI_USE_RESULT indica que queremos usar un conjunto de resultados almacenados en el servidor.
        //Si es MSQLI_STORE_RESULT, los resultados se almacenan en el cliente. (por defecto es STORE_RESULT).
        //Si es MSQLI_USE_RESULT, no se pueden ejecutar otras consultas en la misma conexión hasta que se hayan recuperado todos los resultados.
        //USE_RESULT es útil para conjuntos de resultados grandes que no caben en la memoria del cliente.
        $resultado = $conProyecto->query('SELECT producto, unidades FROM stock', MYSQLI_USE_RESULT);

        $resultado->free();//liberamos el conjunto de resultados
        $conProyecto->close(); //cerramos la conexion
        ?>


                                <!--PUNTO 3.1.3 TRANSACCIONES-->
        <?php
        /*Como ya comentamos, si necesitas utilizar transacciones deberás asegurarte de que estén soportadas 
        por el motor de almacenamiento que gestiona tus tablas en MySQL. 
        Si utilizas InnoDB, por defecto cada consulta individual se incluye dentro de su propia transacción. 
        Puedes gestionar este comportamiento con el método autocommit (función mysqli_autocommit).
        Por defecto viene activado (true). Si lo desactivas (false), deberás usar los métodos commit (función mysqli_commit)*/

        //Desactivando el autocommit aseguramos que varias consultas formen parte de una misma transacción
        $conProyecto->autocommit(false);   // deshabilitamos el modo transaccional automático
        
        //creamos una variable de control
        $exito=true;
        
        //Imaginemos que queremos transferir 100 unidades monetarias de la cuenta A (id 1) a la cuenta B (id 2)

        // A. Restar de la Cuenta A (ID 1)
        $consulta_a = "UPDATE cuentas SET saldo = saldo - 100 WHERE id = 1";
        if (!$conProyecto->query($consulta_a)) {
             $exito = false;
            }

        // B. Sumar a la Cuenta B (ID 2)
        $consulta_b = "UPDATE cuentas SET saldo = saldo + 100 WHERE id = 2";
        if (!$conProyecto->query($consulta_b)) {
           $exito = false;
            }

         // --- 3. Finalizar la Transacción ---
        if ($exito) {
        // Si ambas consultas fueron exitosas
        $conProyecto->commit(); 
         echo " Transferencia completada. Los cambios son permanentes.";
        } else {
         // Si alguna consulta falló, deshacemos todo
            
        $conProyecto->rollback(); 
        echo " ERROR: Transferencia fallida. Se ha deshecho la operación (Rollback).";
    
        // Opcional: mostrar detalles del error
         echo "<p>Detalle del error: " . $conexionDB->error . "</p>";
        }

        // Opcional: volver al modo automático después de la transacción
        $conProyecto->autocommit(true);   


