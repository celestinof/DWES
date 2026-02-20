<?php
// Namespace para que el autoloader de Composer sepa dónde está esta clase
namespace App;

// Importamos PDO para poder usar las constantes como FETCH_ASSOC
use PDO;

/**
 * Clase Voto: Gestiona las lecturas y escrituras en la tabla 'votos'.
 * Hereda de Conexion para aprovechar la conexión PDO que ya tenemos.
 */
class Voto extends Conexion
{
    // El constructor llama al de la clase padre (Conexion.php)
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * isValido: Comprueba si un usuario ya ha votado un producto específico.
     * Según el enunciado, no se puede votar dos veces el mismo producto.
     */
    public function isValido($idPr, $idUs)
    {
        // Busco si existe alguna fila con ese producto y ese usuario
        $consulta = "select * from votos where idPr=:idPr AND idUs=:idUs";
        
        // Uso self::$conexion que es la variable estática de la clase padre
        $stmt = self::$conexion->prepare($consulta);
        
        try {
            $stmt->execute([
                ':idPr' => $idPr,
                ':idUs' => $idUs
            ]);
        } catch (\PDOException $ex) {
            // Si falla la consulta, mato el proceso y muestro el error
            die("Error al consultar validez del voto: " . $ex->getMessage());
        }
        
        // Si rowCount devuelve más de 0, es que ya existe el voto
        $filas = $stmt->rowCount();
        if ($filas > 0) {
            return false; // No es válido porque ya ha votado
        }
        return true; // Es válido, puede votar
    }

    /**
     * insertarVoto: Guarda la nueva puntuación en la base de datos.
     */
    public function insertarVoto($cantidad, $idPr, $idUs) {
        // SQL típico de inserción. Paso los parámetros con nombre por seguridad.
        $insert = "INSERT INTO votos (cantidad, idPr, idUs) VALUES (:cantidad,:idPr,:idUs)";
        $stmt = self::$conexion->prepare($insert);
        
        try {
            $stmt->execute([
                ':cantidad' => $cantidad,
                ':idPr' => $idPr,
                ':idUs' => $idUs
            ]);
        } catch (\PDOException $ex) {
            die("Error al insertar el voto: " . $ex->getMessage());
        }
        
        // Si se ha insertado al menos una fila, es que ha ido bien
        $filas = $stmt->rowCount();
        if ($filas == 0) return false;
        return true;
    }

    /**
     * pintarEstrellas: Esta es la consulta más compleja.
     * Saca la media de votos y el total por producto.
     */
    public function pintarEstrellas() {
        // Uso RIGHT JOIN con productos para que, aunque un producto no tenga votos,
        // aparezca en el listado (aparecerá con media 0).
        // IFNULL sirve para que si no hay votos, la media sea 0 en vez de un valor nulo.
        $consulta = "select p.id, ";
        $consulta .= "IFNULL(sum(cantidad) / count(v.idPr), 0) as mediaVotos, ";
        $consulta .= "count(v.idPr) as numVotos ";
        $consulta .= "from votos v right join productos p ";
        $consulta .= "on v.idPr = p.id group by p.id";
        
        $stmt = self::$conexion->prepare($consulta);
        
        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error al recuperar las estadísticas de votos: " . $ex->getMessage());
        }
        
        // Meto los resultados en un array para pasárselos a la clase Votacion.php
        $data = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        return $data;
    }
}