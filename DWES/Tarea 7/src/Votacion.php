<?php
namespace App;

use Jaxon\Response\Response;

class Votacion {

    public function pintarProductos() {
        $res = new Response();
        $con = Conexion::getConexion();
        // Obtenemos los productos para llenar la tabla [cite: 24]
        $productos = $con->query("SELECT id, nombre, nombre_corto FROM productos")->fetchAll();

        $html = "<table>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Valoración</th>
                        <th>Valorar</th>
                    </tr>";

        foreach ($productos as $p) {
            $id = $p['id'];
            // Llamamos al método obligatorio del enunciado 
            $infoVotacion = $this->pintarEstrellas($id);
            
            $html .= "<tr>
                <td>{$p['nombre_corto']}</td>
                <td>{$p['nombre']}</td>
                <td id='val-$id'>$infoVotacion</td>
                <td>
                    <select id='sel-$id'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                    </select>
                    <button onclick=\"jaxon_Votacion.miVoto($id, document.getElementById('sel-$id').value)\">Votar</button>
                </td>
            </tr>";
        }
        $html .= "</table>";
        
        $res->assign('contenido', 'innerHTML', $html);
        return $res;
    }

    // Método obligatorio: miVoto [cite: 10]
    public function miVoto($idProducto, $puntuacion) {
        $res = new Response();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $usuario = $_SESSION['usuario'];
        $con = Conexion::getConexion();

        // Comprobamos si ya ha valorado [cite: 8, 12]
        $check = $con->prepare("SELECT id FROM votos WHERE id_p = :p AND id_u = :u");
        $check->execute(['p' => $idProducto, 'u' => $usuario]);
        
        if ($check->fetch()) {
            $res->alert("Ya has valorado este producto.");
            return $res;
        }

        // Insertamos el voto [cite: 10]
        $ins = $con->prepare("INSERT INTO votos (id_p, id_u, puntuacion) VALUES (:p, :u, :v)");
        $ins->execute(['p' => $idProducto, 'u' => $usuario, 'v' => $puntuacion]);

        // Actualizamos solo la celda de valoración en tiempo real [cite: 9]
        $nuevoHtml = $this->pintarEstrellas($idProducto);
        $res->assign("val-$idProducto", "innerHTML", $nuevoHtml);
        
        return $res;
    }

    // Método obligatorio: pintarEstrellas 
    public function pintarEstrellas($idProducto) {
        $con = Conexion::getConexion();
        $stmt = $con->prepare("SELECT COUNT(*) as total, AVG(puntuacion) as media FROM votos WHERE id_p = :p");
        $stmt->execute(['p' => $idProducto]);
        $datos = $stmt->fetch();

        if ($datos['total'] == 0) {
            return "Sin valorar";
        }

        $total = $datos['total'];
        $media = round($datos['media'], 2);
        $entera = floor($media);
        $decimal = $media - $entera;

        $html = "$total Valoraciones. ";

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $entera) {
                $html .= '<i class="fas fa-star"></i>'; // Estrella llena
            } elseif ($i == $entera + 1 && $decimal >= 0.5) {
                $html .= '<i class="fas fa-star-half-alt"></i>'; // Media estrella 
            } else {
                $html .= '<i class="far fa-star"></i>'; // Estrella vacía
            }
        }
        return $html;
    }
}