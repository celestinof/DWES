<!----------------PDO-------------------------->

<?php

/*Para conectar una aplicación PHP a una PHP Data Objects, primero se crea un objeto (instancia) de la 
clase PDO.*/

                //Parámetros obligatorios del constructor PDO:
//1. DSN (Data Source Name): Es una cadena que contiene la información necesaria para conectarse a la base de datos,
//    como el tipo de base de datos, el nombre del host, el nombre de la base de datos
// Usaremos "mysql:host=localhost;dbname=proyecto".

//El dsn es la parte más importante para establecer la conexión, ya que contiene el controlador y los parámetros necesarios.
//Dsn significa "Data Source Name" (Nombre de Fuente de Datos) y es una cadena que especifica cómo conectarse a una base de datos específica.
$conProyecto= new PDO("mysql:host=localhost;dbname=proyecto");

//También podemos tenerlo almacenado en variables:

$conProyecto=new PDO($dsn, $user, $pass);
//se recomienda guardar los datos(host, user...) en variables porque si estos cambian
//solo hay que acutalizar el valor de la cariable.
$host="locaalhost";
$db = "proyecto";
$user = "gestor";
$pass = "secreto";
$dsn = "mysql:host=$host;dbname=$db";



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


//Ejemplo continuado de iniciar una conexión PDO, y luego cerrarla al final del script:
$host = "localhost";
$db = "proyecto";
$user = "gestor";
$pass = "secreto";
$dsn = "mysql:host=$host;dbname=$db";
$conProyecto=new PDO($dsn, $user, $pass);
// Configurar el modo de error a advertencias
$conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$conProyecto = null; // Cierra la conexión al final del script

?>

<!----------------Punto 3.2.2. Establecer consultas-------------------------->
<?php
//Una vez establecida la conexión a la base de datos usando PDO, 
// el siguiente paso es realizar consultas SQL para interactuar con los datos
//  almacenados en la base de datos.
//  PDO proporciona varios métodos para ejecutar consultas,
//  siendo los más comunes query() (las que devuelven un conjunto de datos)
//  y prepare()/execute() (las que no devuelven datos).

// Por ejemplo, para eliminar registros de una tabla llamada "stocks"
$registros = $conProyecto->exec('DELETE FROM stocks WHERE unidades=0');
echo "<p>Se han borrado $registros registros.</p>";

//Si devuelve datos (como un select), se usa query(), que devuelve un objeto PDOStatement:
$resultado = $conProyecto->query("SELECT producto, unidades FROM stock");   



//Por defecto PDO trabaja en modo "autocommit", esto es, c
// confirma de forma automática cada sentencia que ejecuta el servidor.
//  Para trabajar con transacciones, PDO incorpora tres métodos:

// 1. beginTransaction. Deshabilita el modo "autocommit" y comienza una nueva transacción, que finalizará cuando ejecutes uno de los dos métodos siguientes.
// 2. commit. Confirma la transacción actual.
// 3. rollback. Revierte los cambios llevados a cabo en la transacción actual.

// Una vez ejecutado un commit o un rollback, se volverá al modo de confirmación automática.

$ok = true;
$conProyecto->beginTransaction();
if(!$conProyecto->exec('DELETE …')) {
    $ok = false;}
if(!$conProyecto->exec('UPDATE …')) {
    $ok = false;}

if ($ok) {
    $conProyecto->commit();} // Si todo fue bien confirma los cambios
else {
    $dwes->rollback();}   //  y si no, los revierte

?>


<!----------------Punto 3.2.3. Establecer consultas-------------------------->
<?php
//partimos de una conexión PDO ya establecida en $conProyecto
//Para acceder a los datos devueltos por una consulta SELECT,
//  PDO ofrece varios métodos de obtención en el objeto PDOStatement,
//  siendo los más comunes fetch() y fetchAll().

//Por defecto, fetch() devuelve cada fila como un array indexado tanto por números enteros (índices) como por nombres de columna (asociativos).
// También puedes especificar el modo de obtención usando los siguientes parámetros:
// PDO::FETCH_BOTH: (por defecto) Devuelve un array que combina ambos métodos anteriores.
$conProyecto = new PDO(". . .");
$resultado = $conProyecto->query("SELECT producto, unidades FROM stocks");
while ($registro = $resultado->fetch()) {
    echo "Producto ".$registro['producto'].": ".$registro['unidades']."<br />";
}
// PDO::FETCH_ASSOC: Devuelve un array asociativo con los nombres de las columnas como claves.
while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
   echo "Producto ".$registro['producto'].": ".$registro['unidades']."<br />";
}
// PDO::FETCH_NUM: Devuelve un array indexado numéricamente.
while ($registro = $resultado->fetch(PDO::FETCH_NUM)) {
   echo "Producto ".$registro[0].": ".$registro[1]."<br />";
}

// PDO::FETCH_OBJ: Devuelve cada fila como un objeto anónimo con las columnas como propiedades.
while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
   echo "Producto ".$registro->producto.": ".$registro->unidades."<br />";
}

//PDO::FETCH_LAZY. Devuelve tanto el objeto como el array con clave dual anterior.
while ($registro = $resultado->fetch(PDO::FETCH_LAZY)) {
   echo "Producto ".$registro->producto.": ".$registro->unidades."<br />";
} //Acceso dual. Sin embargo, este modo es menos eficiente y no se recomienda su uso habitual.

//fetchAll(): Devuelve todas las filas del conjunto de resultados como un array.
$resultados = $resultado->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultados as $registro) {
   echo "Producto ".$registro['producto'].": ".$registro['unidades']."<br />";
}

//fetc_bound(): Permite enlazar columnas específicas a variables PHP.
$resultado = $conProyecto->query("SELECT producto, unidades FROM stocks");
$resultado->bindColumn('producto', $producto);
$resultado->bindColumn('unidades', $unidades);
while ($resultado->fetch(PDO::FETCH_BOUND)) {
   echo "Producto ".$producto.": ".$unidades."<br />";
}

?>

<!----------------Punto 3.2.4. Consultas Preparadas-------------------------->
<?php
//Al igual que con MySQLi, también utilizando PDO podemos preparar consultas parametrizadas en el servidor para ejecutarlas de forma repetida.
//  El procedimiento es similar e incluso los métodos a ejecutar tienen prácticamente los mismos nombres.


// Primero, se prepara la consulta con el método prepare(), que devuelve un objeto PDOStatement.
$conProyecto = new PDO(". . .");
$stmt = $conProyecto->prepare('INSERT INTO familia (cod, nombre) VALUES (?, ?)');

//O también utilizando parámetros con nombre, precediéndolos por el símbolo de dos puntos.
$stmt = $conProyecto->prepare('INSERT INTO familia (cod, nombre) VALUES (:cod, :nombre)');

//Antes de ejecutar la consulta hay que asignar un valor a los parámetros utilizando el método bindParam de la clase PDOStatement.
//  Si utilizas signos de interrogación para marcar los parámetros, el procedimiento es equivalente al método bindColumn que acabamos de ver.

$cod_producto = "TABLET";
$nombre_producto = "Tablet PC";
$consulta->bindParam(1, $cod_producto);
$consulta->bindParam(2, $nombre_producto);

//Si utilizas parámetros con nombre, debes indicar ese nombre en la llamada a bindParam.
$consulta->bindParam(":cod", $cod_producto);
$consulta->bindParam(":nombre", $nombre_producto);

//Finalmente, se ejecuta la consulta con el método execute().
$stmt->execute();

//También existe otra forma de pasar valores a los parámetros. Hay un método lazy, que funciona pasando los valores mediante un array, al método execute().


$nombre="Monitores";
$codigo="MONI";
$stmt = $conProyecto->prepare('INSERT INTO familia (cod, nombre) VALUES (:cod, :nombre)');
$stmt->execute([ ':cod'=>$codigo, ':nombre'=>$nombre]);


?>