<?php
// Usamos el namespace para que el autoloader no se pierda
namespace App;

// Importamos la respuesta de Jaxon para poder interactuar con el HTML desde PHP
use Jaxon\Response\Response;

/**
 * Clase Votacion: Aquí es donde ocurre la magia de las estrellas y los votos.
 * He juntado las funciones que tenías sueltas en una sola clase para Jaxon 5.
 */
class Votacion {

    /**
     * miVoto: Se encarga de guardar la puntuación en la base de datos.
     * Recibe la cantidad (1-5), el ID del producto y el ID del usuario.
     */
    public function miVoto($cantidad, $idPr, $idUs) {
        $resp = new Response();
        // Llamo a la clase Voto (que es la que habla con la tabla de la DB)
        $voto = new Voto();   
        
        // Compruebo si el voto es válido (que no haya votado ya el mismo usuario)
        if (!$voto->isValido($idPr, $idUs)) {
            // Si no es válido, devuelvo false para que el JS sepa que no se ha guardado
            $resp->setReturnValue(false);
        } else {
            // Si todo está bien, inserto el voto en la tabla
            $voto->insertarVoto($cantidad, $idPr, $idUs);
            // Aviso de que se ha guardado correctamente
            $resp->setReturnValue(true);
        }
        return $resp;
    }

    /**
     * pintarEstrellas: Este método recorre todos los productos y dibuja
     * las estrellitas de Font Awesome según la media de votos.
     */
    public function pintarEstrellas() {
        $resp = new Response();
        $voto = new Voto();
        
        // Saco todos los datos de las medias de la base de datos
        $resultado = $voto->pintarEstrellas();
        
        // Recorro el array de resultados fila por fila
        foreach ($resultado as $key => $value) {
            $id = $value['id'];
            $mediaVotos = $value['mediaVotos'];
            $numVotos = $value['numVotos'];
            $innerHTML = '';
            
            // Si el producto no tiene votos, pongo el texto directamente
            if($numVotos == 0){
                $innerHTML = 'Sin valoración';
            } else {
                // Si hay votos, empiezo a construir el HTML con el número de valoraciones
                $innerHTML .= '<p>' . $numVotos . ' valoraciones: ';
                
                // --- Lógica para pintar las estrellas ---
                
                // Si la media tiene decimales (por ejemplo 3.5), hay que ver si ponemos media estrella
                if(floor($mediaVotos) != $mediaVotos){
                    // Pinto las estrellas enteras (la parte de abajo de la media - 1)
                    for ($i=0; $i < floor($mediaVotos); $i++) { 
                        $innerHTML .= '<i class="fas fa-star"></i>';
                    }
                    
                    // Calculo el decimal sobrante
                    $decimal = $mediaVotos - floor($mediaVotos);
                    
                    // Si el decimal es 0.5 o más, pinto la media estrella que pide el enunciado
                    if($decimal >= 0.5){
                        $innerHTML .= '<i class="fas fa-star-half-alt"></i>'; // He puesto -half-alt que es la de Font Awesome 5/6
                    }
                } else {
                    // Si la media es un número entero (3.0), pinto todas enteras
                    for ($i=0; $i < $mediaVotos; $i++) { 
                        $innerHTML .= '<i class="fas fa-star"></i>';
                    }
                }

                $innerHTML .= '</p>';
            }
            
            // Aquí es donde Jaxon busca el ID en el HTML (votos_1, votos_2...) y mete las estrellas
            $resp->assign("votos_" . $id, "innerHTML", $innerHTML);
        }

        // Le digo a Jaxon que todo ha ido bien
        $resp->setReturnValue(true);
        $voto = null; // Limpio el objeto
        return $resp;
    }
}