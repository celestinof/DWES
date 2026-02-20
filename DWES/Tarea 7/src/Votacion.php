<?php
namespace App;

// Añadimos el extends para que $this->response funcione correctamente
class Votacion extends \Jaxon\App\CallableClass {

    public function miVoto($cantidad, $idPr, $idUs) {
        $resp = $this->response;
        $voto = new Voto();           
        
        // 1. Comprobamos si el usuario YA ha votado este producto [cite: 8]
        if (!$voto->isValido($idPr, $idUs)) {
            // Si ya ha votado, solo mostramos la alerta 
            $resp->alert("¡Ya has votado ese producto!"); 
        } 
        else {
            // 2. Si es la primera vez, insertamos el voto [cite: 10]
            $voto->insertarVoto($cantidad, $idPr, $idUs);
            
            // 3. Actualizamos la interfaz en tiempo real sin recargar 
            // Llamamos a pintarEstrellas para que el cambio sea inmediato
            $this->pintarEstrellas();
            $resp->alert("Voto registrado con éxito.");
        }
        
        return $resp;
    }

    public function pintarEstrellas() {
        // Usamos la respuesta de la clase
        $resp = $this->response;
        
        $voto = new Voto();
        $resultado = $voto->pintarEstrellas(); // Debe devolver mediaVotos y numVotos [cite: 13, 19]
        
        foreach ($resultado as $key => $value) {
            $id = $value['id'];
            $mediaVotos = $value['mediaVotos']; // Media aritmética [cite: 17]
            $numVotos = $value['numVotos']; // Cantidad de clientes [cite: 19]
            $innerHTML = '';
            
            if($numVotos == 0){
                $innerHTML = 'Sin valoración';
            } else {
                $innerHTML .= '<p>' . $numVotos . ' valoraciones: ';
                
                // Lógica para pintar estrellas con Font Awesome 
                $parteEntera = floor($mediaVotos);
                for ($i=0; $i < $parteEntera; $i++) { 
                    $innerHTML .= '<i class="fas fa-star"></i>';
                }
                
                // Si la parte decimal es >= 0.5, pintamos media estrella 
                $decimal = $mediaVotos - $parteEntera;
                if($decimal >= 0.5){
                    $innerHTML .= '<i class="fas fa-star-half-alt"></i>'; 
                }

                $innerHTML .= '</p>';
            }
            
            // Asignamos el HTML al elemento correspondiente sin recargar [cite: 9]
            $resp->assign("votos_" . $id, "innerHTML", $innerHTML);
        }

        return $resp;
    }
}