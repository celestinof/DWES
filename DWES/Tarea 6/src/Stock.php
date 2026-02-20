<?php
namespace Clases;

use PDO;

class Stock extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    // Función para saber cuántas unidades hay de un producto en una tienda
    public function recuperarStock($idProducto, $idTienda) {
        $consulta = "SELECT unidades FROM stocks WHERE producto = :p AND tienda = :t";
        $sentencia = self::$conexion->prepare($consulta);
        $sentencia->execute([':p' => $idProducto, ':t' => $idTienda]);
        $resultado = $sentencia->fetch(PDO::FETCH_OBJ);
        
        return ($resultado) ? $resultado->unidades : 0;
    }
}