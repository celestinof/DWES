<!--Parte 3.1. tema 3-->

<?php
// ====================================================================
// PARÁMETROS DE CONEXIÓN
// ====================================================================

// El servidor siempre será 'localhost' o '127.0.0.1' en XAMPP.
$servidor = 'localhost'; 

// Usaremos el usuario con permisos en la base de datos 'proyecto'.
$usuario = 'gestor'; 

// La contraseña que asignaste a 'gestor'.
$contrasena = 'secreto'; 

// La base de datos con la que queremos trabajar.
$base_de_datos = 'proyecto';

// ====================================================================
// FORMA 1: UTILIZANDO LA PROGRAMACIÓN ORIENTADA A OBJETOS (Recomendada)
// ====================================================================

echo "<h2>Forma 1: Orientada a Objetos (new mysqli())</h2>";

// Crear un nuevo objeto de conexión
$conexion_oo = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar si hay errores en la conexión
if ($conexion_oo->connect_error) {
    die("Error de conexión (OO): " . $conexion_oo->connect_error);
}

// Establecer el juego de caracteres a utf8mb4 (Buena práctica)
$conexion_oo->set_charset("utf8mb4");

echo "<p style='color: green;'>¡Conexión exitosa a '$base_de_datos'!</p>";
echo "<p>Versión del servidor: " . $conexion_oo->server_info . "</p>";

// Ejemplo de consulta para demostrar que funciona
$resultado = $conexion_oo->query("SELECT cod, nombre FROM familias LIMIT 2");

if ($resultado->num_rows > 0) {
    echo "<h3>Ejemplo de consulta (Familias):</h3>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "- Código: " . $fila["cod"] . ", Nombre: " . $fila["nombre"] . "<br>";
    }
} else {
    echo "No hay datos en la tabla familias.";
}

// Cerrar la conexión (Buena práctica)
$conexion_oo->close();

// ====================================================================
// FORMA 2: UTILIZANDO FUNCIONES PROCEDURALES
// ====================================================================

echo "<h2>Forma 2: Funciones Procedurales (mysqli_connect())</h2>";

// 1. Establecer la conexión
$conexion_proc = mysqli_connect($servidor, $usuario, $contrasena, $base_de_datos);

// 2. Verificar si hay errores
if (!$conexion_proc) {
    die("Error de conexión (Procedural): " . mysqli_connect_error());
}

// 3. Establecer el juego de caracteres
mysqli_set_charset($conexion_proc, "utf8mb4");

echo "<p style='color: green;'>¡Conexión exitosa a '$base_de_datos'!</p>";
echo "<p>Versión del servidor: " . mysqli_get_server_info($conexion_proc) . "</p>";

// 4. Cerrar la conexión
mysqli_close($conexion_proc);

?>