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
        $conProyecto->close(); //cerramos la conexion

        ?>

                                <!--PUNTO 3.1.4 Obtención y conjunto de resultados-->
        <?php

        //Al ejecutar consultas que devuelven datos (SELECT), obtenemos un objeto resultado (mysqli_result). Una vez ya iniciada la
        //conexión a la base de datos, sin errores realizamos una consulta SELECT.
               
        $consulta1=$conProyecto->query('SELECT producto, unidades FROM stock');

        //Para obtener los datos del objeto resultado, usamos alguno de los métodos de obtención de filas.
        //Posteriormente, recorreremos el conjunto de resultados fila a fila.
        //Existen cuatro métodos principales
        
        //1. fetch_array(): Devuelve una fila del conjunto de resultados como un array indexado tanto por números como por nombres de columna.
        //El parametro opcional MYSQLI_ASSOC, MYSQLI_NUM o MYSQLI_BOTH indica el tipo de array que se devolverá. POR DEFECTO es MYSQLI_BOTH.
        
        //El bucle while recorre todas las filas del conjunto de resultados hasta que fetch_array() devuelve null.
        //Es decir, hasta que no quedan más filas por procesar.
        while ($fila=$consulta1->fetch_array()){
            
            echo "<p>Producto: ".$fila['producto']." - Unidades: ".$fila['unidades']."</p>";
            //También podríamos acceder a los datos usando índices numéricos
            //echo "<p>Producto: ".$fila[0]." - Unidades: ".$fila[1]."</p>";          
        }

        //2. fetch_assoc(): Devuelve una fila del conjunto de resultados como un array asociativo (solo por nombres de columna).
        //Es como fetch_array(MYSQLI_ASSOC)
        $consulta2=$conProyecto->query('SELECT producto, unidades FROM stock');

        while ($fila=$consulta2->fetch_assoc()){
            echo "<p>Producto: ".$fila['producto']." - Unidades: ".$fila['unidades']."</p>";
        }

        //3. fetch_row(): Devuelve una fila del conjunto de resultados como un array indexado numéricamente.
        //Es como fetch_array(MYSQLI_NUM)
        $consulta3=$conProyecto->query('SELECT producto, unidades FROM stock');
        while ($fila=$consulta3->fetch_row()){
            echo "<p>Producto: ".$fila[0]." - Unidades: ".$fila[1]."</p>";
        }

        //4. fetch_object(): Devuelve una fila del conjunto de resultados como un objeto.
        //Los nombres de las columnas se convierten en propiedades del objeto.
        $consulta4=$conProyecto->query('SELECT producto, unidades FROM stock');
        while ($fila=$consulta4->fetch_object()){
            echo "<p>Producto: ".$fila->producto." - Unidades: ".$fila->unidades."</p>";
        }
?>

                                        <!--PUNTO 3.1.5 Consultas preparadas-->
<?php

        //Las consultas preparadas son útiles para ejecutar la misma consulta repetidamente con diferentes valores,
        //y para prevenir ataques de inyección SQL al separar la lógica de la consulta de los datos y por ser
        //procesadas más rápidamente por el servidor de bases de datos.

        //La forma general de usar consultas preparadas con mysqli es utilizar la clase mysqli_stmt.
        //1. Se obtiene el objeto mysqli_stmt llamando al método prepare() del objeto mysqli.

        $stmt = $conProyecto->stmt_init(); //inicializamos el objeto mysqli_stmt

        //2. Preparar la planilla SQL con marcadores de posición (placeholders)
        $stmt->prepare('INSERT INTO familias (cod, nombre) VALUES (?, ?)');

        //3. Vincular los parámetros a los marcadores de posición usando bind_param()
        //se usa "i" para los int, "d" para los double, "s" para los string y "b" para los bools
        //Ejemplo

        //Asignamos los valores a insertar en las variables
        $cod_producto = "TABLET";
        $nombre_producto = "Tablet PC";

        // 'ss' indica que son dos cadenas de texto (string).
        $stmt->bind_param('ss', $cod_producto, $nombre_producto);

        //¡Importante! Los argumentos de bind_param siempre deben ser variables ($cod_producto),
        // no valores literales ("TABLET"), porque se pasan por referencia.

        //4. Tras enlazar, ejecutar la consulta preparada con execute()
        $stmt->execute();

        //5. Finalmente, cerrar la declaración preparada
        $stmt->close();


        //PARA CONSULTAS SELECT PREPARADAS, El proceso es similar,
        //pero después de ejecutar la consulta, se deben vincular las columnas de resultados a variables usando bind_result()
        //y luego recuperar los resultados fila por fila usando fetch().

        // 1. Inicializar el statement
        $stmt_select = $conProyecto->stmt_init(); 

        // 2. Preparar la consulta: Solo necesitamos un placeholder para las unidades
        // Nota: La tabla es 'stocks', no 'stock' (asumiendo que ese fue el nombre usado previamente)
        $stmt_select->prepare('SELECT producto, unidades FROM stocks WHERE unidades > ?');

        // 3. Definir la variable del parámetro
        $unidades_minimas = 10; // Usamos un nombre claro para la variable

        // 4. Vincular el parámetro: 'i' (integer) y la variable $unidades_minimas
        $stmt_select->bind_param("i", $unidades_minimas);

        // 5. Ejecutar la consulta
        $stmt_select->execute();

        // 6. Vincular las columnas de resultados a variables (los nombres pueden ser distintos de las columnas)
        //se guarda en un array asociativo de clave nombre_producto_db y cantidad_unidades_db y valores correspondientes
        $stmt_select->bind_result($nombre_producto_db, $cantidad_unidades_db);

        // 7. Recorrer los resultados con el statement correcto ($stmt_select). Fijarse que usamos fetch() a secas.
        while($stmt_select->fetch()) {
            // Usamos las variables enlazadas con bind_result
            echo "<p>Producto **$nombre_producto_db**: $cantidad_unidades_db unidades.</p>";
        }

        // 8. Cerrar el statement correcto
        $stmt_select->close();