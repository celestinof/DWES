<!----------------PDO-------------------------->

<?php

/*Para conectar una aplicación PHP a una PHP Data Objects, primero se crea un objeto (instancia) de la 
clase PDO.*/

                //Parámetros obligatorios del constructor PDO:
//1. DSN (Data Source Name): Es una cadena que contiene la información necesaria para conectarse a 
// la base de datos, como el tipo de la base de datos, el nombre del host, el nombre de la base de datos
// Usaremos "mysql:host=localhost;dbname=proyecto".

//El dsn es la parte más importante para establecer la conexión,
//  ya que contiene el controlador y los parámetros necesarios.
//Dsn significa "Data Source Name" (Nombre de Fuente de Datos) y es una cadena que especifica 
// cómo conectarse a una base de datos específica.
//tipo de base de datos: mysql
//nombre del host: localhost
//nombre de la base de datos: proyecto

//se recomienda guardar los datos(host, user...) en variables porque si estos cambian
//solo hay que acutalizar el valor de la cariable.

$host="localhost";
$db = "proyecto";
//Aquí tenemos la cadena DSN completa:
$dsn = "mysql:host=".$host.";dbname=".$db;

$user = "gestor";
$pass = "secreto";

/*
Y muy importante para controlar los errores tendremos el atributo: ATTR_ERRMODE  con los posible valores:
ERRMODE_SILENT: El modo por defecto, no muestra errores (
se recomienda en entornos en producción).

ERRMODE_WARNING: Además de establecer el código de error, 
emitirá un mensaje E_WARNING, es el modo empleado para depurar o hacer pruebas para ver errores 
sin interrumpir el flujo de la aplicación.

ERRMODE_EXCEPTION: Además de establecer el código de error, 
lanzará una PDOException que podemos capturar en un bloque try catch(). Lo veremos en el apartado 4.1.
*/
$conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/*Con esto, si ocurre un error en una consulta o en la conexión,
 se lanzará una excepción que podemos capturar y manejar adecuadamente. Esto se ve más adelante*/




$conProyecto=new PDO($dsn, $user, $pass);

//o también directamente sin variables:
$conProyecto= new PDO("mysql:host=localhost;dbname=proyecto");

//Si quisieras indicar al servidor MySQL que utilice codificación UTF-8 o UTF8mb4 (utf8 con soporte para "emojis" muy recomendable) para los datos que se transmitan, aunque hay más formas de hacerlo la siguiente es la más sencilla.
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

// Una vez establecida la conexión, puedes utilizar el método getAttribute
//  para obtener información del estado de la conexión y
//  setAttribute para modificar algunos parámetros que afectan a la misma.

// Por ejemplo, para obtener la versión del servidor puedes hacer:
$version = $conProyecto->getAttribute(PDO::ATTR_SERVER_VERSION);

// Y si quieres por ejemplo que te devuelva todos los nombres de columnas en mayúsculas:
$conProyecto->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);


//Para cerrar la conexión, simplemente hay que destruir el objeto PDO:
$conProyecto = null;
?>

<!----------------Punto 3.2.2. Ejecutar consultas-------------------------->
<?php
//Una vez establecida la conexión a la base de datos usando PDO, 
// el siguiente paso es realizar consultas SQL para interactuar con los datos
//  almacenados en la base de datos.
//  PDO proporciona varios métodos para ejecutar consultas,
//  siendo los más comunes query() (las que devuelven un conjunto de datos)
//  y prepare()/execute() (las que no devuelven datos).

// NO DEVUELVE DATOS. Por ejemplo, para eliminar registros de una tabla llamada "stocks"
$registros = $conProyecto->exec('DELETE FROM stocks WHERE unidades=0');
echo "<p>Se han borrado $registros registros.</p>";
//Fijarse que a diferencia de mysqli, la consulta devuelve un valor en vez de un boolean. Por ello 
//no es necesario comprar ni existe un "affected_rows".

//SI DEVUELVE DATOS (como un select), se usa query(), que devuelve un objeto PDOStatement, similar a mysqli
//que devuelve un objeto mysqli_result.
$resultado = $conProyecto->query("SELECT producto, unidades FROM stock");   



//Por defecto PDO trabaja en modo "autocommit", esto es,
// confirma de forma automática cada sentencia que ejecuta el servidor.
//  Para trabajar con transacciones, es diferente a mysqli, en el que desactivábamos el autocommit
// antes, y lo volvíamos a activar al final. 
// PDO incorpora tres métodos:

// 1. beginTransaction. Deshabilita el modo "autocommit" y comienza una nueva transacción, que finalizará cuando ejecutes uno de los dos métodos siguientes.
// 2. commit. Confirma la transacción actual.
// 3. rollback. Revierte los cambios llevados a cabo en la transacción actual.

// Una vez ejecutado un commit o un rollback, se volverá al modo de confirmación automática.

$ok = true;
$conProyecto->beginTransaction();
// !conProyecto->exec(Consuta SQL); Si da valor distinto a todos menos 0, es decir, si da  0 (falso)
//  asignamos false a $ok
if(!$conProyecto->exec('DELETE …')) {
    $ok = false;}
if(!$conProyecto->exec('UPDATE …')) {
    $ok = false;}

if ($ok) {
    $conProyecto->commit();} // Si todo fue bien confirma los cambios
else {
    $dwes->rollback();}   //  y si no, los revierte

?>


<!----------------Punto 3.2.3.- Obtención y utilización de conjuntos de resultados.-------------------------->
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
//Sería el equivalente a mysqli_stmt_init() + mysqli_stmt_prepare(). 
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