<?php
namespace Clases;

use PDO;

class Familia extends Conexion {
    
    public function __construct() {
        // Al llamar al constructor del padre, nos conectamos a la BD
        parent::__construct();
    }

    // Función para obtener todos los códigos de las familias
    public function recuperarFamilias() {
        $consulta = "SELECT cod FROM familias";
        $sentencia = self::$conexion->prepare($consulta);
        $sentencia->execute();
        // Devolvemos solo la columna 'cod' como un array simple
        return $sentencia->fetchAll(PDO::FETCH_COLUMN);
    }
}