<?php
namespace Clases;

use PDO;

class Producto extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    // Función para obtener el precio (PVP) de un producto
    public function getPrecio($idProducto) {
        $consulta = "SELECT pvp FROM productos WHERE id = :id";
        $sentencia = self::$conexion->prepare($consulta);
        $sentencia->execute([':id' => $idProducto]);
        $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
        
        return ($resultado) ? $resultado->pvp : null;
    }

    // Función para obtener los IDs de productos de una familia concreta
    public function recuperarProductos($codFamilia) {
        $consulta = "SELECT id FROM productos WHERE familia = :f";
        $sentencia = self::$conexion->prepare($consulta);
        $sentencia->execute([':f' => $codFamilia]);
        return $sentencia->fetchAll(PDO::FETCH_COLUMN);
    }
}