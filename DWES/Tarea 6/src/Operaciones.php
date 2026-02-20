<?php
namespace Clases;

// Importamos las clases que acabas de crear para poder usarlas
use Clases\Producto;
use Clases\Familia;
use Clases\Stock;


//MUY IMPORTANTE : Cada método que queramos exponer en el servicio SOAP debe tener la anotación @soap en su comentario PHPDoc. No me di cuenta de esto y estuve una hora para resolverlo.

class Operaciones {

    /**
     * Devuelve el precio de un producto
     * @param int $codigoP
     * @return float
     * @soap
     */
    public function getPvp($codigoP) {
        $producto = new Producto();
        return $producto->getPrecio($codigoP);
    }

    /**
     * Devuelve las unidades de un producto en una tienda
     * @param int $codigoP
     * @param int $codigoT
     * @return int
     * @soap
     */
    public function getStock($codigoP, $codigoT) {
        $stock = new Stock();
        return $stock->recuperarStock($codigoP, $codigoT);
    }

    /**
     * Devuelve un array con los códigos de todas las familias
     * @return string[]
     * @soap
     */
    public function getFamilias() {
        $familia = new Familia();
        return $familia->recuperarFamilias();
    }

    /**
     * Devuelve los códigos de productos de una familia
     * @param string $codigoF
     * @return string[]
     * @soap
     */
    public function getProductosFamilia($codigoF) {
        $producto = new Producto();
        return $producto->recuperarProductos($codigoF);
    }
}