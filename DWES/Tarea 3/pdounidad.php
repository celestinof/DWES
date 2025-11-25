<!----------------PDO-------------------------->

<?php

/*Para conectar una aplicación PHP a una PHP Data Objects, primero se crea un objeto (instancia) de la 
clase PDO.*/

                //Parámetros obligatorios del constructor PDO:
//1. DSN (Data Source Name): Es una cadena que contiene la información necesaria para conectarse a la base de datos,
//    como el tipo de base de datos, el nombre del host, el nombre de la base de datos
// Usaremos "mysql:host=localhost;dbname=proyecto".

$conProyecto= new PDO("mysql:host=localhost;dbname=proyecto");

//También podemos tenerlo almacenado en variables:

$host="localhost";
$dbname="proyecto";
$conProyecto= new PDO("mysql:host=$host;dbname=$dbname");

                //Parametros opcionales del constructor PDO:
//2. Usuario: Nombre de usuario para la conexión a la base de datos. Por defecto es una cadena vacía.
//3. Contraseña: Contraseña para la conexión a la base de datos. Por defectoes una cadena vacía.
//Por ejemplo, el constructor PDO quedaría así:

$conProyecto= new PDO("mysql:host=localhost;dbname=proyecto","root","root");


//ómo establecer y configurar una conexión a una base de datos usando PDO en PHP.
// Establecimiento de Conexiones con PDOEl texto describe el proceso para conectar una aplicación PHP a una base de datos utilizando la extensión PDO (PHP Data Objects), que proporciona una interfaz ligera y consistente para acceder a bases de datos.1. La Clase PDO y sus ParámetrosPara establecer la conexión, debes crear una instancia (un objeto) de la clase PDO. El constructor de esta clase requiere tres parámetros principales, aunque solo el primero es obligatorio:ParámetroDescripciónObligatorio1. Origen de Datos (DSN)Una cadena de texto que especifica el controlador de la base de datos (ej. mysql:) y los parámetros de conexión específicos (servidor, nombre de la BD, etc.).Sí2. Nombre de usuarioEl usuario con permisos para acceder a la base de datos.No3. ContraseñaLa contraseña del usuario.No4. Opciones de conexiónUn array opcional para establecer configuraciones avanzadas de la conexión.No2.
//  La Cadena DSN (Data Source Name)El DSN es la clave de la conexión. Su estructura es: controlador:parámetro1=valor1;parámetro2=valor2;...

//Ejemplo de Código DSN más común: $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
//Instancia de Conexión: 

$conProyecto = new PDO($dsn, $user, $pass);


//Una vez creada la conexión (el objeto $conProyecto), puedes usar métodos para obtener información o modificar su comportamiento.

//Se utilizan los métodos getAttribute() y setAttribute() para consultar y modificar el estado de la conexión.

//Ejemplo de uso de getAttribute() para obtener el modo de error actual:
$errorMode = $conProyecto->getAttribute(PDO::ATTR_ERRMODE);
echo "Current Error Mode: " . $errorMode . "\n";
//Esto devolverá un valor numérico que representa el modo de error actual.

//Ejemplo de uso de setAttribute() para cambiar el modo de error a excepciones:
$conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "Error mode changed to exceptions.\n";
//Después de ejecutar este código, cualquier error en las operaciones de la base de datos lanzará una excepción PDOException, que puedes capturar y manejar en tu aplicación.


//Ejemplo PDO::ATTR_SERVER_VERSION:
$serverVersion = $conProyecto->getAttribute(PDO::ATTR_SERVER_VERSION);
echo "Database Server Version: " . $serverVersion . "\n";
//Esto imprimirá la versión del servidor de la base de datos a la que estás conectado.  

//Ejemplo de uso de PDO::ATTR_CASE:
$conProyecto->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
echo "Column names will be returned in uppercase.\n";
//Después de ejecutar este código, cualquier consulta que realices devolverá los nombres de las columnas


//La conexión permanece activa mientras exista el objeto PDO. Para cerrarla, es necesario destruir ese objeto, eliminando todas las referencias a él. La forma más sencilla de hacer esto es:
//Asignar null a la variable que contiene el objeto PDO: 
$conProyecto = null;
//Esto cerrará la conexión a la base de datos de manera efectiva.