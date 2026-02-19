<?php
namespace App;

use PDO;

class Conexion {
    public static function getConexion() {
        $host = 'localhost';
        $db   = 'proyecto'; 
        $user = 'root';
        $pass = 'gestor'; 

        try {
            return new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (\Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}