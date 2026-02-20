<?php
// Usamos el namespace App para que el autoloader lo encuentre
namespace App;

// Importamos la clase Usuario
use App\Usuario;

class Validar
{
    public function vUsuario($u, $p)
    {
        // Obtenemos la respuesta oficial global de Jaxon (¡sin necesidad de extends!)
        $resp = \Jaxon\jaxon()->getResponse();

        // Si algún campo está vacío, lanzamos la alerta
        if (strlen($u) == 0 || strlen($p) == 0) {
            $resp->alert("¡¡¡ Credenciales Erróneas !!!");
            return $resp;
        } 
        
        $usuario = new Usuario();

        // Comprobamos si las credenciales coinciden en la base de datos
        if (!$usuario->isValido($u, $p)) {
            $resp->alert("¡¡¡ Credenciales Erróneas !!!");
        } 
        else {
            // ¡El usuario es correcto! Iniciamos sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Guardamos el usuario en la sesión
            $_SESSION['usuario'] = $u;
            
            // Le ordenamos al navegador que nos lleve al listado
            $resp->redirect("listado.php");
        }

        // Devolvemos la respuesta para que Jaxon la procese
        return $resp;
    }
}