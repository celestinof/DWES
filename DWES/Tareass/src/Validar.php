<?php
// Usamos el namespace App para que todo esté bien organizado
namespace App;

// Importamos la respuesta de Jaxon, que es lo que enviamos de vuelta al navegador
use Jaxon\Response\Response;

/**
 * Clase Validar: Esta clase se encarga de recibir el usuario y la clave
 * desde el formulario y decirnos si puede entrar o no.
 */
class Validar
{
    /**
     * Este es el método que antes era la función vUsuario en xajax.
     * Recibe el usuario ($u) y el password ($p) que escribió el cliente.
     */
    public function vUsuario($u, $p)
    {
        // Creamos el objeto respuesta de Jaxon para poder contestar
        $resp = new Response();

        // Primero miro si el tío ha dejado algún campo vacío. 
        // Si no ha escrito nada, le decimos directamente que no es válido.
        if (strlen($u) == 0 || strlen($p) == 0) {
            // En Jaxon 5, para devolver un valor simple usamos setReturnValue
            $resp->setReturnValue(false);
        } 
        else {
            // Si hay datos, llamamos a la clase Usuario que hicimos antes
            $usuario = new Usuario();

            // Usamos el método isValido que comprueba el hash sha256 en la base de datos
            if (!$usuario->isValido($u, $p)) {
                // Si la base de datos dice que no existe o la clave está mal...
                $resp->setReturnValue(false);
            } 
            else {
                // ¡Bieeen! El usuario es correcto.
                // Iniciamos la sesión para que el servidor se acuerde de quién es.
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Guardamos el nombre en la sesión (el enunciado decía 'usuario', pero mantengo 'usu' si prefieres)
                $_SESSION['usuario'] = $u;

                // Devolvemos true para que el JavaScript sepa que puede redirigir a la tienda
                $resp->setReturnValue(true);
            }
            
            // Cerramos el objeto usuario para limpiar memoria, aunque PHP lo hace solo
            $usuario = null;
        }

        // Devolvemos la respuesta completa a Jaxon
        return $resp;
    }
}