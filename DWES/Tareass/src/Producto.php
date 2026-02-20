<?php
// Meto el namespace App para que el autoloader de Composer encuentre esta clase
namespace App;

// Tengo que importar la clase PDO para las consultas a la base de datos
use PDO;

/**
 * Clase Producto: Aquí guardo toda la información de los productos de la tienda.
 * Hereda de Conexion para poder usar la base de datos directamente.
 */
class Producto extends Conexion
{
    // Defino las propiedades privadas del producto (las mismas columnas que la base de datos)
    private $id;
    private $nombre;
    private $nombre_corto;
    private $pvp;
    private $famila; // Mantengo la errata 'famila' como en el original por si acaso afecta a la DB
    private $descripcion;

    /**
     * Constructor de Producto. 
     * Llama al constructor de la clase padre (Conexion) para asegurar que estemos conectados.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // --- MÉTODOS GETTER Y SETTER PARA ACCEDER A LAS PROPIEDADES PRIVADAS ---

    // Este me sirve para pillar el ID del producto
    public function getId()
    {
        return $this->id;
    }

    // Este para ponerle un ID nuevo
    public function setId($id)
    {
        $this->id = $id;
    }

    // Para obtener el nombre largo del producto
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    // Este es el nombre corto que se suele usar para códigos o tablas pequeñas
    public function getNombreCorto()
    {
        return $this->nombre_corto;
    }

    public function setNombreCorto($nombre_corto)
    {
        $this->nombre_corto = $nombre_corto;
    }

    // Para saber cuánto cuesta el producto
    public function getPvp()
    {
        return $this->pvp;
    }

    public function setPvp($pvp)
    {
        $this->pvp = $pvp;
    }

    // Para ver a qué familia pertenece
    public function getFamila()
    {
        return $this->famila;
    }

    public function setFamila($famila)
    {
        $this->famila = $famila;
    }

    // Para leer la descripción detallada
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * listadoProductos: Este método es clave porque saca todos los productos de la base de datos.
     * Devuelve el objeto $stmt con los resultados para poder recorrerlos luego con un bucle.
     */
    public function listadoProductos()
    {
        // Preparo la consulta SQL para traerme todo ordenado por ID
        $consulta = "select * from productos order by id";
        
        // Uso self::$conexion que es la variable estática que heredamos de Conexion.php
        $stmt = self::$conexion->prepare($consulta);
        
        try {
            // Intento ejecutar la consulta
            $stmt->execute();
        } catch (\PDOException $ex) {
            // Si algo falla al leer la tabla, que me avise y pare todo
            die("Error al recuperar los productos: " . $ex->getMessage());
        }
        
        // Devuelvo el resultado de la consulta (un cursor de PDO)
        return $stmt;
    }
}