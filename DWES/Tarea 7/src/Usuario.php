<?php
// Como siempre, pongo el namespace App para que el cargador de archivos me encuentre
namespace App;

// Traigo PDO para que no de errores al preparar las consultas
use PDO;

/**
 * Clase Usuario: Esta clase sirve para manejar todo lo relacionado con los 
 * usuarios que intentan entrar en nuestra aplicación.
 * Hereda de Conexion para poder usar la base de datos que ya configuramos.
 */
class Usuario extends Conexion
{
    // Propiedades privadas para guardar el nombre de usuario y la contraseña
    private $usuario;
    private $pass;

    /**
     * Constructor de la clase Usuario.
     * Al llamar a parent::__construct(), nos aseguramos de que la conexión
     * a la base de datos esté lista nada más crear un objeto de tipo Usuario.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * isValido: Este método es fundamental. Comprueba si el usuario y la contraseña
     * que han metido en el formulario son correctos y existen en la tabla de usuarios.
     * * @param string $u El nombre de usuario que viene del formulario
     * @param string $p La contraseña en texto plano que viene del formulario
     */
    public function isValido($u, $p)
    {
        // El profe pide que las contraseñas estén encriptadas. 
        // Uso la función hash con el algoritmo sha256 para que coincida con lo que hay en la DB.
        $pass1 = hash('sha256', $p);
        
        // Preparo la consulta SQL. Uso marcadores (:u y :p) por seguridad para evitar inyecciones SQL.
        // Ojo con el pass.
        $consulta = "select * from usuarios where usuario=:u AND pass=:p";
        
        // Uso self::$conexion porque es la variable que heredamos de la clase Conexion
        $stmt = self::$conexion->prepare($consulta);
        
        try {
            // Ejecuto la consulta pasando los valores reales a los marcadores
            $stmt->execute([
                ':u' => $u,
                ':p' => $pass1
            ]);
        } catch (\PDOException $ex) {
            // Si la consulta falla (por ejemplo, si la tabla no existe), mostramos el error
            die("Error al consultar usuario: " . $ex->getMessage());
        }
        
        // Cuento cuántas filas ha devuelto la consulta
        $filas = $stmt->rowCount();
        
        // Si el número de filas es 0, es que el usuario o la clave están mal
        if ($filas == 0) {
            return false;
        }
        
        // Si ha encontrado una fila, es que todo está correcto
        return true;
    }
}