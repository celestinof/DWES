<?php
namespace Clases; // <--- AÑADIR ESTO (Según tema 2.1)

use PDO; // Importante: al usar namespace, clases nativas como PDO necesitan 'use' o poner \PDO
use PDOException;

//Creamos la conexión a la BBDD
class Conexion {

    //atributos de la clase
    private $host="localhost";
    private $dbname="practicaunidad5";
    private $usuario="gestor";
    private $pass="secreto";
    private $dsn="";
    //atributo para la conexión, protegido para que solo las clases hijas puedan acceder a él
    protected $conexion;

    //usamos el constructor para inicializar la conexión
    public function __construct(){
        
        $this->dsn="mysql:host=".$this->host.";dbname=".$this->dbname;
        try {
        $this->conexion=new PDO($this->dsn,$this->usuario,$this->pass);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e){

        die ("Error de conexión)". $e->getMessage());}
}
}
?>



    
