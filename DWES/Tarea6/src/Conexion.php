<?php
// Usamos el nombre del "paquete" que definimos en el autoload
namespace Clases;

// Importamos la herramienta PDO que viene con PHP para bases de datos
use PDO;
use PDOException;

class Conexion {
    // Aquí guardaremos la conexión para que todas las funciones la usen
    protected static $conexion;

    // El constructor se ejecuta solo al crear el objeto
    public function __construct() {
        // Si no hay conexión todavía, la creamos
        if (self::$conexion === null) {
            $this->hacerConexion();
        }
    }

    // Este es el método que abre la puerta a la base de datos
    private function hacerConexion() {
        $servidor = "localhost";
        $base_datos = "tarea6";
        $usuario = "gestor";
        $clave = "secreto";
        
        // Configuramos la frase de conexión (DSN)
        $dsn = "mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4";

        try {
            // Intentamos conectar
            self::$conexion = new PDO($dsn, $usuario, $clave);
            
            // Le decimos a PHP que si hay un fallo, nos avise con un error claro (Excepción)
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $error) {
            // Si algo falla (ej: clave mal puesta), nos lo dirá aquí
            die("Error al conectar con la tienda: " . $error->getMessage());
        }
    }
}