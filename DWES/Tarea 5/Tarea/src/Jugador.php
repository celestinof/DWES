<?php

namespace Clases; // <--- AÑADIR ESTO (Según tema 2.1) 

use PDO; // Importante: al usar namespace, clases nativas como PDO necesitan 'use' o poner \PDO
use PDOException;

//Creamos la clase jugador que hereda de conexión
class Jugador extends Conexion {

    private $id;
    private $nombre;
    private $apellidos;
    private $dorsal;
    private $posicion;

    //creamos el constuctor de clase jugador. Dejo valores por defecto a los atributos
    public function __construct($id="", $nombre="", $apellidos="", $dorsal=0 , $posicion=""){

        //llamamos al constructor de la clase padre para inicializar la conexión
        parent::__construct();

        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->dorsal=$dorsal;
        $this->posicion=$posicion;

    }

    /** NOTA para los setter y getter mágicos. Como no podemos controlar si la propiedad existe
     * al ser un método común para todas las propiedades, he encontrato que usando el método "property_exists"
     * podemos comprobar si la propiedad existe en la clase y así evitar errores.
     */

    //creamos método get mágico para los atributos
    public function __get($propiedad) {
    //con el this indicamos que buscamos en la clase actual la propiedad
    if (property_exists($this, $propiedad)) {
        return $this->$propiedad;
    }
}

    //creamos el método set mágico para los atributos
    public function __set($propiedad, $valor) {
    if (property_exists($this, $propiedad)) {
        $this->$propiedad = $valor;
    }
}

    public function create(){
        //intentamos insertar en la BBDD
        try {
        //Creamos la consulta preparada para insertar un jugador en la tabla jugadores de la base de datos
        $stmt = $this->conexion->prepare("INSERT INTO jugadores (nombre, apellidos, dorsal, posicion) VALUES (:n,:ape,:dor,:pos)");
        //vinculamos los parámetros de la consulta con los atributos de la clase
        $stmt->bindParam(":n", $this->nombre);
        $stmt->bindParam(":ape", $this->apellidos);
        $stmt->bindParam(":dor",$this->dorsal);
        $stmt->bindParam(":pos",$this->posicion);
        //ejecutamos la consulta
        $stmt->execute();}

        //error en la inserción de la BBDD
        catch (PDOException $e) {
            echo "error al crear el jugador". $e->getMessage();
        }}
     //fin método create

    //función para mostrar a los jugadores
    public function mostrarJugadores(){
        $stmt=$this->conexion->prepare("SELECT * FROM jugadores");
        $stmt->execute();
        $arrayJugadores=$stmt->fetchALL();
        return $arrayJugadores;}



    //función para eliminar jugadores
    public function delete($id){
    
    try{
        $stmt=$this->conexion->prepare("DELETE from jugadores WHERE id=:id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        }catch(PDOException $e) {
            echo "Error al eliminar el jugador: " . $e->getMessage();}
    }

//para ver si existe dorsal
public function existeDorsal($d) {
    $stmt = $this->conexion->prepare("SELECT id FROM jugadores WHERE dorsal = :d");
    $stmt->execute([':d' => $d]);
    return $stmt->rowCount() > 0; // Devuelve true si ya existe
}

//para ver si hay datos en la tabla jugadores
public function hayDatos() {
    $stmt = $this->conexion->prepare("SELECT id FROM jugadores LIMIT 1");
    $stmt->execute();
    return $stmt->rowCount() > 0; // Para saber si hay que ir a instalacion.php 
}

} //fin clase jugador