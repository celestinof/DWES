<?php
// Defino el namespace para que Jaxon y el Autoload encuentren la clase sin problemas
namespace App;

// Tengo que importar PDO y PDOException porque al poner el namespace arriba,
// PHP las buscaría dentro de "App" y daría error. Con esto le digo que use las globales.
use PDO;
use PDOException;

class Conexion
{
    // Esta variable estática guardará la conexión para que sea única en toda la ejecución.
    // La pongo protected para que solo se pueda tocar desde aquí o clases que hereden.
    protected static $conexion;

    /**
     * El constructor: lo que se ejecuta al hacer un "new Conexion()"
     */
    public function __construct()
    {
        // Compruebo si la conexión ya existe. Si es null, es que todavía no hemos conectado.
        if (self::$conexion == null) {
            // Llamo al método que hace el trabajo sucio de conectar
            self::crearConexion();
        }
    }

    /**
     * Este método es el que realmente abre la puerta a la base de datos MySQL
     */
    public static function crearConexion()
    {
        // Datos de acceso que nos da el enunciado de la Tarea 7
        $user = "gestor";   // El usuario de la base de datos
        $pass = "secreto";    // La contraseña que pide el ejercicio
        $base = 'proyecto'; // El nombre de la base de datos que creamos con el SQL
        
        // El DSN (Data Source Name) es como la dirección de envío: host, base de datos y el idioma (charset)
        // Uso utf8mb4 para que no me salgan símbolos raros con las tildes de los productos
        $dsn = "mysql:host=localhost;dbname=$base;charset=utf8mb4";
        
        try {
            // Intento crear el objeto PDO que es nuestra conexión real
            self::$conexion = new PDO($dsn, $user, $pass);
            
            // Le digo a PDO que si algo sale mal (un error de SQL), lance una Excepción.
            // Esto es súper útil para que no se quede la pantalla en blanco sin saber qué pasa.
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $ex) {
            // Si entra aquí es que la conexión ha petado (clave mal, servidor apagado...)
            // Con el die() mato el proceso y muestro el mensaje de error para poder arreglarlo.
            die("Error en la conexión: mensaje: " . $ex->getMessage());
        }
    }

    /**
     * Método extra para que otras clases (como Votacion) puedan pillar la conexión 
     * sin tener que crear un objeto nuevo de esta clase.
     */
    public static function getConexion() {
        if (self::$conexion == null) {
            self::crearConexion();
        }
        return self::$conexion;
    }
}